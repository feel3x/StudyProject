
let prevMouseX = 0;
let prevMouseY= 0;
let isMoving = false;
let isResizing = false;
let moveId = "";
let stackOrder = 1;
let panelCount = 0;
let panelIndex = 0;

var minimizedPanels = {};

//Warn user before closing the browser window if there are any Panels opened
window.onbeforeunload = function() {
    if (panelCount>0) {
      return 'There are currently some Windows opened. Are you sure you want to leave?';
    }
    return undefined;
  }

/**
    Brings a panel to the front of the stack by increasing its z-index.
    @param {string} panelId - The id of the panel to bring to the front.
    */
function bringToFront(panelId)
{
    stackOrder++;
    document.getElementById(panelId).style.zIndex = stackOrder; 
}

/**
    Minimizes a panel by hiding its body and changing its dimensions.
    @param {string} panelId - The id of the panel to minimize.
    */
function minimizePanel(panelId)
{
    var panel = document.getElementById(panelId);
    if(panel.getElementsByClassName('panelBody')[0].style.display == "none")
    {
        var panelBody =  panel.getElementsByClassName('panelBody')[0];
        $(panelBody).slideDown(500);
      $(panel).animate({width: "50%", left: "20px", top: "20px", height: "50%"}, 500);
        document.getElementById(panelId).style.position = "absolute";
        minimizedPanels[panelId] = 0;
    }
    else
    {
      var panelBody =  panel.getElementsByClassName('panelBody')[0];
      $(panelBody).slideUp(500);
    
    $(panel).animate({width: "200px", left: "0px", top: "0px", height: "30px"}, 500);
        document.getElementById(panelId).style.position = "relative";
        document.getElementById(panelId).style.float="left";
        minimizedPanels[panelId] = 1;
    }
}

/**
    Makes a panel fullscreen by changing its dimensions.
    @param {string} panelId - The id of the panel to make fullscreen.
    */
function makeFullscreen(panelId)
{
    //if panel is currently minimized, show panel
    if(document.getElementById(panelId).getElementsByClassName('panelBody')[0].style.display == "none")
    {
        $(document.getElementById(panelId).getElementsByClassName('panelBody')[0]).show(0);
        document.getElementById(panelId).style.position = "absolute";
       
    }
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
updateFontSize(panelId);
minimizedPanels[panelId] = 0;
}

/**
    Updates the font size of a panel based on its dimensions.
    @param {string} panelId - The id of the panel to update the font size for.
    */
function updateFontSize(panelId)
{
    var compStyles = window.getComputedStyle(document.getElementById(panelId));
    var newSize = ((parseInt(compStyles.getPropertyValue('width'))/parseInt($(window).width()))*50);
    if(newSize>15)
    {
        newSize = 15;
    }
    document.getElementById(panelId).getElementsByClassName("panelContent")[0].style.fontSize = newSize+"px";
}

//Array of refresh Timers
var refreshTimers = {};

/**
    Closes a panel by destroying it and clearing all the refreshTimers.
    @param {string} panelId - The id of the panel to close.
    */
function closePanel(panelId)
{
    clearTimeout(refreshTimers[panelId]);
    var panel = document.getElementById(panelId);
    $(panel).hide(500, function() {
        panel.remove();
    });
    panelCount--;
}

/**
    Saves a refresh timer for a panel.
    @param  timer - The refresh timer to save.
    @param {string} panelId - The id of the panel to save the timer for.
    */
function saveRefreshTimer(timer, panelId)
{
    refreshTimers[panelId] = timer;
}

/**
    Starts moving a panel based on mouse movement.
    @param {string} panel - The id of the panel to start moving.
    */
function startMovingPanel(panel)
{
    if(minimizedPanels[panel] != null && minimizedPanels[panel] == 1)
    {
        return;
    }
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

/**
    Begins resizing a panel based on mouse movement.
    @param {string} panel - The id of the panel to start resizing.
    */
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

/**
    Moves or resizes a panel based on mouse movement.
    */
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
            updateFontSize(moveId);
            prevMouseX = currentMouseX;
        prevMouseY = currentMouseY; 
    }
}

/**
    Stops the moving or resizing of a panel.
    */
function stopMovingPanel()
{
    isMoving = false;
    isResizing = false;
    prevMouseX = 0;
    prevMouseY = 0;

}

/**
    Sets the title of a panel.
    @param {Element} element - The title element from the page displayed within this panel.
    @param {string} title - The fallback title if there is no title element.
    */
function setPanelTitle(element, title)
{
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

/**
    Creates a new panel with the given URL.
    @param {string} url - The URL to display in the panel.
    */
function createPanel(url)
{
    panelCount++;
    panelIndex++;
    var div = document.createElement('div');
div.innerHTML = document.getElementById('panelComponent').innerHTML;
div.innerHTML = div.innerHTML.replace(/{PANELNUMBER}/g, panelIndex)
    document.getElementById('root').appendChild(div);
goTo(div.getElementsByClassName('panelContent')[0], url);
var panel = div.getElementsByClassName('floatingPanel')[0];
var prevStyle = getComputedStyle(panel);
var prevWidth = prevStyle.getPropertyValue('width');
var prevHeight = prevStyle.getPropertyValue('height');
panel.style.width = "0px";
panel.style.height = "0px";
bringToFront(panel.id);
$(panel).animate({width: prevWidth, height: prevHeight}, 500);
}
