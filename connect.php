<?php
session_start();
$dsn = "mysql:host=remotemysql.com;dbname=wDXhbnlTle;charset=utf8mb4";
$options = [
  PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
];
try {
  $db = new PDO($dsn, "wDXhbnlTle", "xOh2hFurkr", $options);
  
} catch (Exception $e) {
  error_log($e->getMessage());
  exit('Something weird happened'); //something a user can understand
}
$_SESSION['userPermissions'] = array(1,2,3,4,5,6,7,8,9,10);
$_SESSION['userId'] = 1;
$_SESSION['environmentId'] = 1;
?>