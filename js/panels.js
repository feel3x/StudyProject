
let prevMouseX = 0;
let prevMouseY= 0;
let isMoving = false;
let isResizing = false;
let moveId = "";
let stackOrder = 1;
let panelCount = 0;


function bringToFront(panelId)
{
    stackOrder++;
    document.getElementById(panelId).style.zIndex = stackOrder; 
}

function minimizePanel(panelId)
{
    var panel = document.getElementById(panelId);
    if(panel.getElementsByClassName('panelBody')[0].style.display == "none")
    {
        var panelBody =  panel.getElementsByClassName('panelBody')[0];
        $(panelBody).slideDown(500);
      $(panel).animate({width: "50%", left: "20px", top: "20px"}, 500);
        document.getElementById(panelId).style.position = "absolute";
    }
    else
    {
      var panelBody =  panel.getElementsByClassName('panelBody')[0];
      $(panelBody).slideUp(500);
    $(panel).animate({width: "200px", left: "0px", top: "0px"}, 500);
    document.getElementById(panelId).style.float="left";
    document.getElementById(panelId).style.position = "relative";
    }
}
function makeFullscreen(panelId)
{
   var compStyles = window.getComputedStyle(document.getElementById(panelId));
    if(document.getElementById(panelId).style.width == "100%" &&  document.getElementById(panelId).style.height == "100%")
    {
        document.getElementById(panelId).style.width = "50%";
    document.getElementById(panelId).style.height = "50%";
    document.getElementById(panelId).style.left = "20px";
    document.getElementById(panelId).style.top = "20px";    
    }
    else
    {
     document.getElementById(panelId).style.width = "100%";
    document.getElementById(panelId).style.height = "100%";
    document.getElementById(panelId).style.left = "0px";
    document.getElementById(panelId).style.top = "0px";    
    }
    document.getElementById(panelId).getElementsByClassName("panelContent")[0].style.fontSize = ((parseInt(compStyles.getPropertyValue('width'))/parseInt($(window).width()))*50)+"px";
}

function closePanel(panelId)
{
    var panel = document.getElementById(panelId);
    $(panel).hide(500, function() {
        panel.remove();
    });
    
}

function startMovingPanel(panel)
{
    if(event.touches != null && event.touches.length > 0)
    {
    var touch = event.touches[0];
var prevMouseX = touch.clientX;
var prevMouseY = touch.clientY;
    }
    else
    {
    var e = window.event;


var prevMouseX = e.clientX;
var prevMouseY = e.clientY;
    }
isMoving = true;
moveId = panel;
bringToFront(panel);
}
function startResizingPanel(panel)
{
    if(event.touches != null && event.touches.length > 0)
    {
    var touch = event.touches[0];
var prevMouseX = touch.clientX;
var prevMouseY = touch.clientY;
    }
    else
    {
    var e = window.event;


var prevMouseX = e.clientX;
var prevMouseY = e.clientY;
    }
isResizing = true;
moveId = panel;
}

function movePanel()
{   
    if(event.touches != null && event.touches.length > 0)
    {
    var touch = event.touches[0];
var currentMouseX = touch.clientX;
var currentMouseY = touch.clientY;
    }
    else
    {
    var e = window.event;


var currentMouseX = e.clientX;
var currentMouseY = e.clientY;
    }
if(prevMouseX == 0)
{
    mouseDeltaX = 0;
}
else
{
 mouseDeltaX =  prevMouseX - currentMouseX;    
}
if(prevMouseY == 0)
{
    mouseDeltaY = 0;
}
else
{
    mouseDeltaY = prevMouseY - currentMouseY;    
}
 
    if(isMoving)
    {
        compStyles = window.getComputedStyle(document.getElementById(moveId));
        rootStyles = window.getComputedStyle(document.getElementById("root"));
            document.getElementById(moveId).style.left =   (parseInt(compStyles.getPropertyValue('left')) - mouseDeltaX) + "px";
            document.getElementById(moveId).style.top =   (parseInt(compStyles.getPropertyValue('top')) - mouseDeltaY) + "px";
            if(parseInt(compStyles.getPropertyValue('left')) < 0)
            {
                document.getElementById(moveId).style.left = "0px";
            }
            if(parseInt(compStyles.getPropertyValue('top')) < 0)
            {
                document.getElementById(moveId).style.top = "0px";
            }
            if(parseInt(compStyles.getPropertyValue('right')) < 0)
            {
                document.getElementById(moveId).style.left = (parseInt(rootStyles.getPropertyValue("width")) - parseInt(compStyles.getPropertyValue("width")))+"px" ;
            }
            if(parseInt(compStyles.getPropertyValue('bottom')) < 0)
            {
                document.getElementById(moveId).style.top = (parseInt(rootStyles.getPropertyValue("height")) - parseInt(compStyles.getPropertyValue("height")))+"px" ;
            }
            
        prevMouseX = currentMouseX;
        prevMouseY = currentMouseY;
    } else
    if(isResizing)
    {
        compStyles = window.getComputedStyle(document.getElementById(moveId));
        rootStyles = window.getComputedStyle(document.getElementById("root"));
            document.getElementById(moveId).style.width =   (parseInt(compStyles.getPropertyValue('width')) - mouseDeltaX) + "px";
            document.getElementById(moveId).style.height =   (parseInt(compStyles.getPropertyValue('height')) - mouseDeltaY) + "px";
            if(parseInt(compStyles.getPropertyValue('width')) < 100)
            {
                document.getElementById(moveId).style.width = "100px";
            }
            if(parseInt(compStyles.getPropertyValue('height')) < 100)
            {
                document.getElementById(moveId).style.height = "100px";
            }
            if(parseInt(compStyles.getPropertyValue('right')) < 0)
            {
                document.getElementById(moveId).style.width = (parseInt(rootStyles.getPropertyValue("width")) - parseInt(compStyles.getPropertyValue("left")))+"px" ;
            }
            if(parseInt(compStyles.getPropertyValue('bottom')) < 0)
            {
                document.getElementById(moveId).style.height = (parseInt(rootStyles.getPropertyValue("height")) - parseInt(compStyles.getPropertyValue("top")))+"px" ;
            }
            document.getElementById(moveId).getElementsByClassName("panelContent")[0].style.fontSize = ((parseInt(compStyles.getPropertyValue('width'))/parseInt($(window).width()))*50)+"px";
            prevMouseX = currentMouseX;
        prevMouseY = currentMouseY; 
    }
}

function stopMovingPanel()
{
    isMoving = false;
    isResizing = false;
    prevMouseX = 0;
    prevMouseY = 0;

}

function setPanelTitle(element, title)
{
    console.log("hier");
 var content = element.closest('.panelTitle');
 if(content != null)
 {
    content.innerHTML = title;
 }
 else
 {
     console.log("null");
 }
}


function createPanel(url)
{
    panelCount++;
    var div = document.createElement('div');
div.innerHTML = document.getElementById('panelComponent').innerHTML;
div.innerHTML = div.innerHTML.replace(/{PANELNUMBER}/g, panelCount)
    document.getElementById('root').appendChild(div);
goTo(div.getElementsByClassName('panelContent')[0], url);
var panel = div.getElementsByClassName('floatingPanel')[0];
var prevStyle = getComputedStyle(panel);
var prevWidth = prevStyle.getPropertyValue('width');
var prevHeight = prevStyle.getPropertyValue('height');
panel.style.width = "0px";
panel.style.height = "0px";
$(panel).animate({width: prevWidth, height: prevHeight}, 500);
}