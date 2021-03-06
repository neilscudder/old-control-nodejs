// CTRL.JS 0.4.1 Copyright 2015 @neilscudder
// Licenced under the GNU GPL <http://www.gnu.org/licenses/>

require('dotenv').load()
var https = require('https')
  , fs = require('fs')
  , childProcess = require('child_process')
  , url = require('url')
  , MongoClient = require('mongodb').MongoClient
  , assert = require('assert')
  , jade = require('jade')
  , path = require('path')
  , uuid = require('node-uuid')
  , querystring = require('querystring')
  , static = require('node-static')

var options = {
      key: fs.readFileSync(process.env.SSL_KEY),
      cert: fs.readFileSync(process.env.SSL_CRT),
    }
  , apiUrl = process.env.APIURL
  , apiAlt = process.env.APIALT
  , serverListenPort = process.env.PORT
  , mongourl = process.env.MONGOURL
  , file = new static.Server( './res', {
      cache: 0,
    } )

console.log('Starting https server...')
https.createServer(options, function(req,res){
  if (process.setuid && (process.getuid() == 0)) {
    console.log('Current uid: ' + process.getuid());
    try {
      process.setuid(1000);
      console.log('New uid: ' + process.getuid());
    }
    catch (err) {
      console.log('Failed to set uid: ' + err);
    }
  }
  console.log('Server responding on port ' + serverListenPort)
  var url_parts = url.parse(req.url, true)
  var query = url_parts.query

  function authenticate(cmd) {
    MongoClient.connect(mongourl, function(err, db) {
      assert.equal(null, err)
      lookupKey(db, query['KPASS'], function() {
          db.close()
      })
    }) 
    var lookupKey = function(db, key, callback) {
       var cursor =db.collection('playnodeca').find( { 'control': key } )
       cursor.each(function(err, doc) {
          assert.equal(err, null)
          if (doc != null) {
               console.log('Access Granted')
               childProcess.exec(cmd,returnData)
          } else {
               console.log('Access Denied ' + key )
               res.end('Access Denied','utf8')
          }
       })
    }
  }

  function parsePost(callback) {
    var data = ''
    req.on('data', function(chunk) {
      data += chunk
    })
    req.on('end', function() {
      var obj = querystring.parse(data)
      var readable = JSON.stringify(obj, null, 4)
      console.log('Post data parsed ' + readable)
      callback(obj)
    })
  }

  function authorize(data) {
    var controlURL
      , resetURL
      , rkey
      , ckey
    // TODO validate data here
    controlURL = data.CONTROLSERVER + '/?'
    if (data.MPDPASS !== '' && data.MPDHOST !== '') controlURL += 'MPDPASS=' + data.MPDPASS + '&MPDHOST=' + data.MPDHOST
    if (data.MPDPASS == '' && data.MPDHOST !== '') controlURL += '&MPDHOST=' + data.MPDHOST
    if (data.MPDPORT !== '') controlURL += '&MPDPORT=' + data.MPDPORT
    if (data.LABEL !== '') controlURL += '&LABEL=' + data.LABEL
    if (data.EMAIL !== '') controlURL += '&EMAIL=' + data.EMAIL
    if (apiUrl !== '') controlURL += '&APIURL=' + apiUrl
    if (apiAlt !== '') controlURL += '&APIALT=' + apiAlt
    controlURL += '&KPASS='
    resetURL = controlURL
    rkey = uuid.v4()
    ckey = uuid.v4()
    resetURL += rkey
    controlURL += ckey
    console.log(controlURL) 
    // CHEAT: setting oldRKey:
    var oldRKey = rkey
    MongoClient.connect(mongourl, function(err, db) {
      assert.equal(null, err)
      upsertKeys(db,function() {
          db.close()
      })
    }) 
    var upsertKeys = function(db,callback) {
       var collection = db.collection('playnodeca')
       collection.update({reset:rkey},{reset:rkey,control:ckey},{upsert:true},function upsertCB(err) {
         assert.equal(null, err)
       })
    }   
    authority(controlURL,resetURL)
  }

  function authority(controlURL,resetURL){
    console.log('Authority: ' + controlURL) 
    fs.readFile('authority.jade', 'utf8', function (err,data) {
      if (err) {
        return console.log(err)
      }
      console.log('Rendering the authority')
      var fn = jade.compile(data, {
        filename: path.join(__dirname, 'authority.jade'),
        pretty:   true
      })
      var htmlOutput = fn({
        url: {
          control: controlURL,
          reset: resetURL
        }
      })
      res.end(htmlOutput,'utf8')
    })
  }

  function showControlGUI() {
    fs.readFile('index.jade', 'utf8', function (err,data) {
      if (err) {
        return console.log(err)
      }
      console.log('Showing the Control GUI')
      var fn = jade.compile(data, {
        filename: path.join(__dirname, 'index.jade'),
        pretty:   true
      })
      var htmlOutput = fn({
        control: {
          APIURL: query['APIURL'],
          APIALT: query['APIALT'],
          MPDPORT: query['MPDPORT'],
          MPDHOST: query['MPDHOST'],
          MPDPASS: query['MPDPASS'],
          LABEL: query['LABEL'],
          KPASS: query['KPASS']
        }
      })
      res.end(htmlOutput,'utf8')
    })
  }

  function processCommand() {
    console.log(query)
    var mpc, pass, host, port
    pass = query['MPDPASS']
    host = query['MPDHOST']
    port = query['MPDPORT']
    mpc = '/usr/bin/mpc'
    if (pass && host) mpc += ' -h ' + pass + '@' + host
    if (host && !pass) mpc += ' -h' + host
    if (port) mpc += ' -p ' + port
    mpc += ' '
    res.statusCode = 200
    res.setHeader("Access-Control-Allow-Origin", "*")
    console.log(query['a'])
    switch(query['a']) {
      case 'info':
        // To refresh the DIV with id = info
      	res.setHeader("Content-Type","text/html")
      	var cmd = 'sh/mpdStatus.sh ' + '"' + mpc + '"'
      	childProcess.exec(cmd,returnData)
      break;
      case 'up':
      	mpc += ' volume +5'
      	res.setHeader("Content-Type","text/html")
      	var cmd = 'sh/cmd.sh ' + '"' + mpc + '"'
        authenticate(cmd)
      break;
      case 'dn':
      	mpc += ' volume -5'
      	res.setHeader("Content-Type","text/html")
      	var cmd = 'sh/cmd.sh ' + '"' + mpc + '"'
      	authenticate(cmd)
      break;
      case 'fw':
      	mpc += ' next'
      	var cmd = 'sh/cmd.sh ' + '"' + mpc + '"'
      	authenticate(cmd)
      break;
      case 'random':
      	mpc += ' random'
      	var cmd = 'sh/cmd.sh ' + '"' + mpc + '"'
      	authenticate(cmd)
      break;
      default:
      	result = 'No match case'
      	returnData()
    }
  }
  function returnData(err,stdout,stderr){
    if (err) {
      console.log(err)
    } else if (stdout) {
      res.end(stdout,'utf8')
    } else {
      res.end
    }
    if (stderr) console.log(stderr)
  } 
  
  if (query['a']) {
    console.log('Process command: ' + query['a'])
    processCommand()
  } else if (req.url == '/authority') {
    console.log('Authority Vanilla')
    authority()
  } else if (req.method == 'POST'){
    console.log('Authority data POST')
    parsePost(authorize)
  } else if (req.url.indexOf('/res') > -1) {
    console.log('serving ' + req.url)
    file.serve(req,res)
    res.end()
  } else {
    console.log('Show GUI')
    showControlGUI()
  }
}).listen(serverListenPort, "0.0.0.0")

