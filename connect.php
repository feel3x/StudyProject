<?php
session_start();
$dsn = "mysql:host=localhost;dbname=trampcomp;charset=utf8mb4";
$options = [
  PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
];
try {
  $db = new PDO($dsn, "root", "", $options);
  
} catch (Exception $e) {
  error_log($e->getMessage());
  exit('Something weird happened'); //something a user can understand
}

if(!isset($_SESSION['userId']))
{
$_SESSION['userPermissions'] = array(1,2,3,16);
}

$_SESSION['environmentId'] = 1;

//make an array with all permissions to find them by name
$stmt = $db->prepare("SELECT * FROM `permissions`");
   $stmt->execute();
 while($getPermissionRow = $stmt->fetch(PDO::FETCH_ASSOC))
 {
$GLOBALS['permissionByName'][$getPermissionRow['title']] = $getPermissionRow['id'];
 }

function checkPermission($permissionName)
{
  if (in_array(($GLOBALS['permissionByName'])[$permissionName], $_SESSION['userPermissions']) || in_array(11, $_SESSION['userPermissions']))
  {
    return true;
  }
  else
  {
    return false;
  }
}


?>