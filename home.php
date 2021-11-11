<?php
include("connect.php");
?>
<!DOCTYPE html>
<html>
<head>
<title>Trampoline Competition Tool</title>
</head>

<script src="jquery/jquery-3.6.0.js"></script>
<link rel="stylesheet" href="css/panel.css">
<link rel="stylesheet" href="css/loader.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<script id="panelComponent" type="text/html">
    <div id="movingPanel{PANELNUMBER}" class="floatingPanel">
        <div class="panelHeader" onMouseDown="startMovingPanel('movingPanel{PANELNUMBER}')" onTouchStart="startMovingPanel('movingPanel{PANELNUMBER}')">
        <div style="float:left; margin: 5px; display: none" class="backButton"><a href="#" class="panelAction" style="color: rgb(34, 34, 34)" onClick="goBack('movingPanel{PANELNUMBER}')"><i class="fas fa-long-arrow-alt-left"></i></a></div>       
                <div style="float:left; margin: 5px;"><a href="#" class="panelAction" style="color: rgb(34, 34, 34)" onClick="closePanel('movingPanel{PANELNUMBER}')"><i class="fas fa-times-circle"></i></a></div>
               <div style="float:left; margin: 5px;"><a href="#" class="panelAction" style="color: rgb(34, 34, 34)"  onClick="minimizePanel('movingPanel{PANELNUMBER}')"><i class="fas fa-window-minimize"></i></a></div>
        <div style="float:left; margin: 5px;"><a href="#" class="panelAction" style="color: rgb(34, 34, 34)"  onClick="makeFullscreen('movingPanel{PANELNUMBER}')"><i class="fas fa-expand-arrows-alt"></i></a></div>



        <div class="panelTitle">New Window</div>
        </div>
        <div class="panelBody">
            <div id="panelContent{PANELNUMBER}" class="panelContent">
            </div>
        <div
        class="panelResizeHandle" onMouseDown="startResizingPanel('movingPanel{PANELNUMBER}')" onTouchStart="startResizingPanel('movingPanel{PANELNUMBER}')">
        </div>
        </div>

        </div>
</script>
<script id="loaderComponent" type="text/html">
    <div class="loadingDiv">
        <div class="loadingBody firstLine"></div>
        <div class="loadingBody secondLine"></div>
        <div class="loadingBody thirdLine"></div>
      </div>
</script>
<script src="js/panels.js"></script>

<style>

@keyframes disappear {
    0% {
      transform: rotateX(0deg) rotateY(0deg) rotateZ(0deg);
    }
    100% {
      transform: rotateX(0deg) rotateY(90deg) rotateZ(0deg);
    }
  }

.disappearElements 
{
    animation-name: disappear;
  animation-duration: .3s;
  animation-fill-mode: forwards; 
}

body
{
    touch-action: none;
    background-image: url("./img/background.jpg");
    background-size: cover;
    background-attachment:scroll;

}

html, body, form, fieldset, table, tr, td, img, input, button, select, textarea, optgroup, option {
    font-family: inherit;
    font-size: inherit;
    font-style: inherit;
    font-family: Arial, Helvetica, sans-serif;

}

#root 
{
    position: absolute;
     background-color: rgba(255, 255, 255, 0.795);
width: 100vw;
height: 100%;
    min-height: 100%;
left:0;
top:0;
}
#rootContent
{ 
    position: absolute;
    width: 100%;
    height: 100%;
    overflow-y: auto;
}

#fullscreen_button
{
    border: 3px dashed black;
    background-color: yellow;
}
#back_button
{
    display:none;
  position:absolute;
    border-bottom-right-radius: 15px;
    left: 0px;
    top:0px;
}
</style>
<script type="text/javascript">

history = {};

function disappearElements(content)
{
  var allElements = content.children;
  for(var i = 0; i<allElements.length; i++)
  {
      allElements[i].classList.add("disappearElements");
  }
}

function addLoader(content)
{
    var addedDiv = document.createElement('div');
    $(addedDiv).hide(0);
    addedDiv.innerHTML = document.getElementById('loaderComponent').innerHTML;
    content.appendChild(addedDiv);
    $(addedDiv).fadeIn(1000);

    return addedDiv;

}
function printContent(element) {
    var panel = element.closest(".floatingPanel");
    var printSection = panel.getElementsByClassName("sectionToPrint")[0];
     var printContents = printSection.innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}

function refreshPanel(panelId)
{
    if(panelId != null)
    {
    var lastUrl = history[panelId][history[panelId].length - 1];
        if(lastUrl != undefined)
        {
            var content = document.getElementById(panelId).getElementsByClassName('panelContent')[0];
            var headerTitle = document.getElementById(panelId).getElementsByClassName('panelTitle')[0];
        $(content).load(lastUrl,'', function(result) {
            var newTitle = content.getElementsByClassName("title")[0];
        if(newTitle == null)
        {
            newTitle = content.getElementsByTagName("title")[0].innerHTML;
        }
        else
        {
            newTitle = newTitle.innerHTML;
        }
        headerTitle.innerHTML = newTitle;      
});

}
}
}

function goTo(element, url)
{
   
    var panelId;
    if(element != null)
    {
 var content = element.closest('.panelContent');
 if(content != null)
 {
 var panel = content.closest('.floatingPanel');
 var headerTitle = panel.getElementsByClassName('panelTitle')[0];
 panelId = panel.id;
 }
 else
 {
     panelId = "root";
 }
}
else
{
    panelId = "root"; 
}
if(!(panelId in history))
{
    history[panelId] = [];
}
    history[panelId].push(url);

 if(content != null)
 {
    if(history[panelId].length>1)
    {
        panel.getElementsByClassName('backButton')[0].style.display = "block";
    }
    disappearElements(content);
    addLoader(content);
     $(content).load(url,'', function(result) {
        var newTitle = content.getElementsByClassName("title")[0];
        if(newTitle == null)
        {
            newTitle = content.getElementsByTagName("title")[0].innerHTML;
        }
        else
        {
            newTitle = newTitle.innerHTML;
        }
        headerTitle.innerHTML = newTitle;

        $(content).hide(0);
         $(content).fadeIn(1000);
    });
 }
 else
 {
    var rootContent = document.getElementById('rootContent');
    if(history["root"].length>1)
    {
        document.getElementById('back_button').style.display = "block";
    }
    disappearElements(rootContent);

    addLoader(rootContent);
    $(rootContent).load(url,'', function(result) {
        var newTitle = rootContent.getElementsByClassName("title")[0];
        if(newTitle == null)
        {
            newTitle = "";
        }
        else
        {
            newTitle = newTitle.innerHTML;
        }
        document.getElementById('rootTitle').innerHTML = newTitle;

        $(rootContent).hide(0);
         $(rootContent).fadeIn(1000);
    });
 }
}

function goBack(element)
{ 
    var elementId = element;
    if(element != null && element != undefined)
    {
        element = document.getElementById(element);
 var content = element.getElementsByClassName('panelContent')[0];
}  
else
{
    elementId = "root";
}
if(elementId in history)
{
    if(history[elementId].length > 1)
    {
        urlArray = history[elementId].slice();
        goTo(content, urlArray[history[elementId].length-2]);
        urlArray.pop();
        history[elementId] = urlArray;
    }
}
}


$(document).ready(function(){
 $("#fullscreen_button").on("click", function() 
 {
  document.fullScreenElement && null !== document.fullScreenElement || !document.mozFullScreen && !document.webkitIsFullScreen ? document.documentElement.requestFullScreen ? document.documentElement.requestFullScreen() : document.documentElement.mozRequestFullScreen ? document.documentElement.mozRequestFullScreen() : document.documentElement.webkitRequestFullScreen && document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT) : document.cancelFullScreen ? document.cancelFullScreen() : document.mozCancelFullScreen ? document.mozCancelFullScreen() : document.webkitCancelFullScreen && document.webkitCancelFullScreen()
 });	
});
	
window.onload = function() {
    goTo(null, "modules/start.php");
};


      </script>
      <body onmousemove="movePanel()" onmouseup="stopMovingPanel()" ontouchmove="movePanel()" ontouchend="stopMovingPanel()">

<div  id="root">

   <div id="rootContent">
    </div>  
    
    
  <div id="rootTitle" style="top:0; right:0; padding-left: 10px; padding-right: 5px; position:absolute; border-bottom-left-radius: 15px; background-color:rgba(0, 0, 0, 0.685); color: white">
</div>
 <div class="position-absolute" id="fullscreen_button">
 </div>
<div id="back_button" class="btn-primary" onclick="goBack(null)"><i class="fas fa-long-arrow-alt-left fa-lg mx-2"></i></div>
</div>

</body>
</html> 