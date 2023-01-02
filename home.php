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
<script src="https://kit.fontawesome.com/83c037d386.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="jquery/jquery-3.6.0.js"></script>

<!-- Style Sheets -->
<link rel="stylesheet" href="css/panel.css">
<link rel="stylesheet" href="css/loader.css">
<link rel="stylesheet" href="css/pageStyle.css">

<!-- Components to create during runtime -->
<script id="panelComponent" src="panelComponent.html" type="text/html"></script>
<script id="loaderComponent" src="loaderComponent.html" type="text/html"></script>
<script id="errorMessage" src="errorMessageCompinent.html" type="text/html"></script>

<!-- JS scripts -->
<script src="js/panels.js"></script>
<script src="js/panelsFunctions.js"></script>
<script src="./js/PatienceDiff.js"></script>
<script src="./js/diff_match_patch.js"></script>


<script type="text/javascript">
    window.onload = function () {
        goTo(null, "modules/start.php");
    };
</script>

<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<body onmousemove="movePanel()" onmouseup="stopMovingPanel()" ontouchmove="movePanel()" ontouchend="stopMovingPanel()">

    <div id="root">

        <div id="rootContent">
        </div>


        <div id="rootTitle"
            style="top:0; right:0;margin-left: 50px; padding-left: 10px; padding-right: 5px; position:absolute; border-bottom-left-radius: 15px; background-color:rgba(0, 0, 0, 0.685); color: white">
        </div>
        <div style="bottom:0px; right: 5px; width: 40px; height:40px" class="position-absolute" id="fullscreen_button">
            <i class="fas fa-expand-arrows-alt fa-2x"></i>
        </div>
        <div id="back_button" class="btn-primary" onclick="goBack(null)"><i
                class="fas fa-long-arrow-alt-left fa-lg mx-2"></i></div>
    </div>

</body>

</html>