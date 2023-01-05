<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once("connect.php");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Trampoline Competition Tool</title>
</head>

<!-- Embedding of frameworks, JQeury, Bootstrap, Fontawesome Icons -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="jquery/jquery-3.6.0.js"></script>
<script src="https://kit.fontawesome.com/83c037d386.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Style Sheets -->
<link rel="stylesheet" href="css/panel.css">
<link rel="stylesheet" href="css/loader.css">
<link rel="stylesheet" href="css/pageStyle.css">

<!-- Components to create during runtime -->
<!-- Panel Component -->
<script id="panelComponent" type="text/html">
    <div id="movingPanel{PANELNUMBER}" onmouseover="panelInactive('movingPanel{PANELNUMBER}', true)" onmouseout="panelInactive('movingPanel{PANELNUMBER}')" class="floatingPanel">
        <div class="panelHeader" onMouseDown="startMovingPanel('movingPanel{PANELNUMBER}')" onTouchStart="startMovingPanel('movingPanel{PANELNUMBER}')">
            <div class="panelButtons">
                <div style="float:left; margin: 5px; display: none" class="backButton"><a href="#" class="panelAction" style="color: rgb(34, 34, 34)" onClick="goBack('movingPanel{PANELNUMBER}')"><i class="fas fa-long-arrow-alt-left"></i></a></div>
                <div style="float:left; margin: 5px;"><a href="#" class="panelAction" style="color: rgb(34, 34, 34)" onClick="closePanel('movingPanel{PANELNUMBER}')"><i class="fas fa-times-circle"></i></a></div>
                <div style="float:left; margin: 5px;"><a href="#" class="panelAction" style="color: rgb(34, 34, 34)" onClick="minimizePanel('movingPanel{PANELNUMBER}')"><i class="fas fa-window-minimize"></i></a></div>
                <div style="float:left; margin: 5px;"><a href="#" class="panelAction" style="color: rgb(34, 34, 34)" onClick="makeFullscreen('movingPanel{PANELNUMBER}')"><i class="fas fa-expand-arrows-alt"></i></a></div>
            </div>



            <div class="panelTitle">New Window</div>
        </div>
        <div class="panelBody">
            <div id="panelContent{PANELNUMBER}" class="panelContent">
            </div>
            <div class="panelResizeHandle" onMouseDown="startResizingPanel('movingPanel{PANELNUMBER}')" onTouchStart="startResizingPanel('movingPanel{PANELNUMBER}')">
            </div>
        </div>

    </div>
</script>

<!-- loader Component -->
<script id="loaderComponent" src="loaderComponent.txt" type="text/html">
    <div class="loadingDiv">
        <div class="loadingBody firstLine"></div>
        <div class="loadingBody secondLine"></div>
        <div class="loadingBody thirdLine"></div>
    </div>
</script>

<!-- Error Message Component -->
<script id="errorMessage" src="errorMessageCompinent.txt" type="text/html">
    <div class="errorDiv m-10">
        <h6 class="errorTitle">Error</h6>
        <div class="errorContent p-10"></div><br>
        <button class="errorButton btn-primary p-10">OK</button>
    </div>
</script>
<!-- End of Components -->

<!-- JS scripts -->
<script src="./js/PatienceDiff.js"></script>
<script src="./js/diff_match_patch.js"></script>
<script src="js/panels.js"></script>
<script src="js/siteFunctions.js"></script>


<script type="text/javascript">
    window.onload = function() {
        goTo(null, "modules/start.php");
    };
</script>

<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<body onmousemove="movePanel()" onmouseup="stopMovingPanel()" ontouchmove="movePanel()" ontouchend="stopMovingPanel()">

    <div id="root">

        <div id="rootContent">
        </div>


        <div id="rootTitle" style="top:0; right:0;margin-left: 50px; padding-left: 10px; padding-right: 5px; position:absolute; border-bottom-left-radius: 15px; background-color:rgba(0, 0, 0, 0.685); color: white">
        </div>
        <div style="bottom:0px; right: 5px; width: 40px; height:40px" class="position-absolute" id="fullscreen_button">
            <i class="fas fa-expand-arrows-alt fa-2x"></i>
        </div>
        <div id="back_button" class="btn-primary" onclick="goBack(null)"><i class="fas fa-long-arrow-alt-left fa-lg mx-2"></i></div>
    </div>

</body>

</html>