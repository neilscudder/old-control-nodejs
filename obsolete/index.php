<?php
// GUI 0.3.0 Copyright 2015 @neilscudder
// Licenced under the GNU GPL <http://www.gnu.org/licenses/>

setlocale(LC_CTYPE, "en_US.UTF-8"); // Fixes non ascii characters with escapeshellarg

if (isset($_GET["m"])) {
  $MPDPORT=$_GET["m"];
}
if (isset($_GET["h"])) {
  $MPDHOST=$_GET["h"];
}
if (isset($_GET["p"])) {
  $PASSWORD=$_GET["p"];
}
if (isset($_GET["k"])) {
  $KPASS=$_GET["k"];
}
if (isset($_GET["l"])) {
  $LABEL=$_GET["l"];
} elseif (isset($MPDHOST)){
  $LABEL="Music server: $MPDHOST";
} else {
  $LABEL="Music server: localhost";
}
?>

<!DOCTYPE html>

<head>
<title><?php echo $LABEL; ?></title>
<meta charset="UTF-8">
<meta 
  name="viewport"
  content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" 
/>
<link rel="icon" sizes="192x192" href="res/icon_playnode.png">
<link rel="manifest" href="res/chromescreen.json">
</head>

<body class="" ontouchstart="">
  <nav>
    <div class="row">
      <div id="dn" class="animated quarter released">
        <svg 
          class="toolbar" 
          id="dn"
          x="0px" 
          y="0px"
          viewBox="0 0 24 24" 
          enable-background="new 0 0 24 24" 
          xml:space="preserve"
        >
          <path 
            fill="#002B36" 
            d="M18.5,12c0-1.77-1.02-3.29-2.5-4.03v8.05C17.48,15.29,18.5,13.77,18.5,12z M5,9v6h4l5,5V4L9,9H5z"
          />
          <path 
            fill="none" 
            d="M0,0h24v24H0V0z"
          />
          <image 
            src="res/volDn_colour.png" 
            xlink:href="">
        </svg>
      </div>  
      <div 
        id="up" 
        class="animated quarter released">
        <svg 
          class="toolbar" 
          id="up" 
          x="0px" 
          y="0px"
          viewBox="0 0 48 48" 
          enable-background="new 0 0 48 48" 
          xml:space="preserve">
          <path fill="#002B36" d="M6,18v12h8l10,10V8L14,18H6z M33,24c0-3.54-2.04-6.58-5-8.06v16.1C30.96,30.58,33,27.54,33,24z M28,6.46
            v4.12C33.779,12.3,38,17.66,38,24s-4.221,11.7-10,13.42v4.12c8.02-1.82,14-8.979,14-17.54C42,15.44,36.02,8.28,28,6.46z" />
          <path 
            fill="none" 
            d="M0,0h48v48H0V0z"
          />
          <image 
            src="res/volUp_colour.png" 
            xlink:href=""> 
        </svg>
      </div>   
      <div 
        id="fw" 
        class="animated quarter released">
        <svg 
          class="toolbar" 
          id="fw" 
          x="0px" 
          y="0px"
          viewBox="0 0 48 48" 
          enable-background="new 0 0 48 48" 
          xml:space="preserve">
          <path 
            fill="#002B36" d="M12,36l17-12L12,12V36z M32,12v24h4V12H32z" />
          <path 
            fill="none" 
            d="M0,0h48v48H0V0z"/>
          <image 
            src="res/fw_colour.png" 
            xlink:href=""> 
        </svg>
      </div>
      <div 
        id="togBrowser" 
        class="animated quarter released">
          <svg 
          class="toolbar"
          x="0px" 
          y="0px"
          viewBox="0 0 48 48" 
          enable-background="new 0 0 48 48" 
          xml:space="preserve">
            <path fill="none" d="M0,0h48v48H0V0z"/>
            <path fill="#002B36" d="M30,12H6v4h24V12z M30,20H6v4h24V20z M6,32h16v-4H6V32z M34,12v16.359C33.38,28.141,32.7,28,32,28
              c-3.32,0-6,2.68-6,6s2.68,6,6,6s6-2.68,6-6V16h6v-4H34z"/>
          </svg>
      </div> 
    </div>
    <div class="row">
      <div class="banner">
        <h3><?php echo $LABEL;?></h3> 
      </div>
    </div>
  </nav>    
  <main>
    <!-- Viewer -->
    <section id="info">just a sec..
    </section>
    <!-- END Viewer -->    
  </main>


<style type="text/css">
  html {   
    position: relative;    
    min-height: 100%;    
    -webkit-touch-callout: none;    
    -webkit-user-select: none;    
    -khtml-user-select: none;    
    -moz-user-select: none;    
    -ms-user-select: none;    
    user-select: none;
    -webkit-tap-highlight-color: rgba(0,0,0,0);
}    
body {    
  margin: 0 0 60px 0;    
  max-width: 1200px;    
  background-color: #002b36 ;    
  color: #eee8d5 ;    
  font-family: sans-serif;   
  font-size: 120%;    
  font-weight: 400;     
}
ul {  
  border: 0;  
  margin: 90px 0 0 0;
  padding: 0;  
}   
li {  
  border-top: 1px solid #073642;
  padding: 0;  
}  
img.playbtn {    
  height: 60px;    
  display: block;    
  margin-left: auto;  
  margin-right: auto;  
}   
svg.play {    
  height: 40px;    
  display: block;    
  margin: auto;
} 
svg.toolbar {  
  pointer-events: none;
  height: 60px;  
  display: block;  
  margin-left: auto;  
  margin-right: auto;  
}    
.playPath {
  fill: #93A1A1;
}

nav { 
  display: table; 
  overflow: hidden;  
  margin: 0;  
  padding: 0;  
  height: 60px;  
  line-height: 60px;  
  text-align: center;
  background-color: #073642;
  position: fixed;  
  top: 0;  
  width: 100%;  
  max-width: 1200px;  
  z-index: 100;  
}
.row {
  display: table;
  width: 100%;
}  
.banner h3 {
  padding: 3px;
  margin: 0;
  font-size: .8em;
  line-height: 20px;
}
#info{
  padding-top: 100px;
}
.info-container{
        margin: 0px auto 30px auto;
}
iframe {
  width: 100%;
  max-width: 700px;
  height: 430px;
}
#dn { 
  background-color: #b58900;
}
#up { 
  background-color: #cb4b16;
}
#fw { 
  background-color: #dc322f;
}
#togBrowser {  
  background-color: #d33682;
  color: #002b36;
  font-weight:800;
  font-size: 3.75em;
}
#tog a, a:active { 
  text-decoration: none;
  color: #002b36;
}
.quarter {  
  display: table-cell;  
  height: 60px;  
  line-height: 60px;  
  width: 25%; 
  border-radius: 15px; 
  border: 3px solid #002b36;
}    
.playbtn {  
  height: 100%;   
  width: 80px;
  float: right;
}   
.button { 
  display: table-cell;  
  width: 200px;
  height: 60px;
  line-height: 60px;
  background-color: #268bd2;
  border: 3px solid #002b36;
  border-radius: 16px;
  text-align: center;
  text-decoration: none;
}
.button a { 
  text-decoration: none;
  font-weight: 800;
  color: #002b36; 
}

.animated {
  -webkit-animation-duration: .12s;
  animation-duration: .12s;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
  transform: translate3d(0,0,0);
  -webkit-transform: translate3d(0,0,0);
}
.heartbeat {
  -webkit-animation-duration: 10s;
  animation-duration: 10s;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
  transform: translate3d(0,0,0);
  -webkit-transform: translate3d(0,0,0);
  -webkit-animation-name: beater;
  animation-name: beater;
}
.opaque { opacity: 1 }
.pushed {
  -webkit-animation-name: pusher;
  animation-name: pusher;
}
.timeout {
  -webkit-animation-name: pusher;
  animation-name: pusher;
}
.released {
  -webkit-animation-name: releaser;
  animation-name: releaser;
}
.denied {
  -webkit-animation-name: denier;
  animation-name: denier;
}
.confirm {
  -webkit-animation-name: confirmer;
  animation-name: confirmer;
  height: 100px;
}
path.confirm {
  fill: red;
  width: 1.5em;
  height: 1.5em;
}

@-webkit-keyframes beater {
  0% { opacity: 1 }
  100% { opacity: 0}
}
@keyframes beater {
  0% { opacity: 1 }
  100% { opacity: 0}
}
@-webkit-keyframes pusher {
  0% {
    -webkit-transform: scale3d(1, 1, 1);
    transform: scale3d(1, 1, 1);
  }
  100% {
    -webkit-transform: scale3d(1, 0, 1);
    transform: scale3d(1, 0, 1);
  }
}

@keyframes pusher {
  0% {
    -webkit-transform: scale3d(1, 1, 1);
    transform: scale3d(1, 1, 1);
  }
  100% {
    -webkit-transform: scale3d(1, 0, 1);
    transform: scale3d(1, 0, 1);
  }
}
@-webkit-keyframes releaser {
  0% {
    -webkit-transform: scale3d(0, 1, 1);
    transform: scale3d(0, 1, 1);
  }
  100% {
    -webkit-transform: scale3d(1, 1, 1);
    transform: scale3d(1, 1, 1);
  }
}

@keyframes releaser {
  0% {
    -webkit-transform: scale3d(0, 1, 1);
    transform: scale3d(0, 1, 1);
  }
  100% {
    -webkit-transform: scale3d(1, 1, 1);
    transform: scale3d(1, 1, 1);
  }
}
@-webkit-keyframes denier {
  0% {
    -webkit-transform: scale3d(0, 1, 1);
    transform: scale3d(0, 1, 1);
    opacity: 0.1;
  }
  100% {
    -webkit-transform: scale3d(1, 1, 1);
    transform: scale3d(1, 1, 1);
    opacity: 0.5;
  }
}

@keyframes denier {
  0% {
    -webkit-transform: scale3d(0, 1, 1);
    transform: scale3d(0, 1, 1);
    opacity: 0.1;
  }
  100% {
    -webkit-transform: scale3d(1, 1, 1);
    transform: scale3d(1, 1, 1);
    opacity: 0.5;
  }
}
@-webkit-keyframes confirmer {
  0% {
    -webkit-transform: scale3d(0, 1, 1);
    transform: scale3d(0, 1, 1);
  }
  100% {
    -webkit-transform: scale3d(2,2,2);
    transform: scale3d(2,2,2);
  }
}
@keyframes confirmer {
  0% {
    -webkit-transform: scale3d(0, 1, 1);
    transform: scale3d(0, 1, 1);
  }
  100% {
    -webkit-transform: scale3d(2, 2, 2);
    transform: scale3d(2, 2, 2);
  }
}
</style>



<div class="MPDPORT" id="<?php echo $MPDPORT; ?>"></div>
<div class="MPDHOST" id="<?php echo $MPDHOST; ?>"></div>
<div class="PASSWORD" id="<?php echo $PASSWORD; ?>"></div>
<div class="LABEL" id="<?php echo $LABEL; ?>"></div>
<div class="KPASS" id="<?php echo $KPASS; ?>"></div>


<script language="javascript" type="text/javascript">
var controlScript = "https://playnode.ca:8000/"
var clickEventType = ((document.ontouchstart!==null)?'click':'touchstart')
var PreviousInfo
var MPDPORT = document.getElementsByClassName("MPDPORT")[0].id
var MPDHOST = document.getElementsByClassName("MPDHOST")[0].id
var PASSWORD = document.getElementsByClassName("PASSWORD")[0].id
var KPASS = document.getElementsByClassName("KPASS")[0].id

function getCmd(id){ 
  var x = document.getElementById(id)
  var xhr = new XMLHttpRequest()
  var params = controlScript
  params += "?a=" + id
    + "&m=" + MPDPORT 
    + "&h=" + MPDHOST
    + "&p=" + PASSWORD
    + "&k=" + KPASS;
  xhr.open("GET",params,true)
  xhr.send()
  xhr.onreadystatechange = function() {
    if (xhr.status == 200 && xhr.readyState == 4 && x.classList.contains("pushed")) {
      x.classList.add('released')
      x.classList.remove('pushed')
    } else if (xhr.readyState == 4 && x.classList.contains("pushed")) {
//      alert(xhr.responseText)
      x.classList.add('denied')
      x.classList.remove('pushed')
    } else {
      // Nothing
    }
  }
}

function autoRefresh(id) {
  var x = document.getElementById('info')
  x.classList.remove('opaque')
  x.classList.add('heartbeat')
  
  setTimeout(function(){ autoRefresh(id) },1500)
  var ahr = new XMLHttpRequest()
  var params = controlScript
  params += "?a=" + id
    + "&m=" + MPDPORT 
    + "&h=" + MPDHOST
    + "&p=" + PASSWORD
    + "&k=" + KPASS;
  ahr.open("GET",params,true)
  ahr.send()
  ahr.onreadystatechange = function() {
    if (ahr.readyState == 4 && ahr.status == 200) {
      var CurrentInfo = ahr.responseText;
      if (CurrentInfo !== PreviousInfo && !isEmpty(CurrentInfo)) {
        var infoWin = document.getElementById(id)
        infoWin.innerHTML = CurrentInfo
        window.PreviousInfo = CurrentInfo
        playListener()
        animatedButtonListener()
      }
      x.classList.remove('heartbeat')
      x.classList.add('opaque')
    }
  }
}
function isEmpty(str) {
    return (!str || 0 === str.length)
}
function initialise() {
  var id = document.getElementsByTagName('section')[0].id
  autoRefresh(id)
  animatedButtonListener()
}

//
// LISTENERS
//
function pushed(id){
    document.getElementById(id).classList.add('pushed')
    document.getElementById(id).classList.remove('released')
}
function animatedButtonListener() {
  var buttons = document.getElementsByClassName("animated")
  function pusher(e){
    var id = e.currentTarget.id
    var x = document.getElementById(id)
    if (x.classList.contains("released") && id.match(/tog/g)) {
      pushed(id)
      togBrowser(id)
    } else if (x.classList.contains("released")) {
      pushed(id)
      getCmd(id)
    }
  }
  for(i = 0; i<buttons.length; i++) {
      buttons[i].addEventListener(clickEventType, pusher, false)
  }
}
function playListener() {
  var playButton = document.getElementsByClassName("play")
  function otherPusher(e) {
    var nid = e.currentTarget.id
    var x = document.getElementById(nid)
    if (x.classList.contains("confirm")) {
      postCmd("play",nid)
      window.location.href = "index.php?MPDPORT=" + window.MPDPORT + "&LABEL=" + window.LABEL
    } else {
      x.classList.add('pushed')
      x.classList.remove('released')
    }
  }
  function confirmer(e) { 
    var id = e.currentTarget.id
    var x = document.getElementById(id)
    var shapes
    if (x.classList.contains("pushed")) {
        x.classList.add('confirm')
        shapes = x.getElementsByClassName("playPath")
        shapes[0].style.fill = "#eee8d5"
        x.classList.remove('pushed')
    } else if (x.classList.contains("confirm")) {
        setTimeout(function(){ buttonTimeout(id) },2200)
    } else {
        x.classList.add('released')
        shapes = x.getElementsByClassName("playPath")
        shapes[0].style.fill = "#93A1A1"
    }
  }
  function buttonTimeout(id) {
    document.getElementById(id).classList.remove("confirm")
    document.getElementById(id).classList.add('released')
  }
  for(var i=0; i<playButton.length; i++) {
      playButton[i].addEventListener(clickEventType, otherPusher, false)
      playButton[i].addEventListener("animationend", confirmer, false)
      playButton[i].addEventListener("webkitAnimationEnd", confirmer, false)
  }
}
initialise()
</script>
</body>
</html>



