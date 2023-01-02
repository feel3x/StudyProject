<?php include("../includeSafety.php"); ?>
<script type = "text/javascript">
function LOGINreload()
{
  window.location.href = "home.php";
}
</script>
<?php

if(isset($_POST['identifier']))
{
  $identifier = $_POST['identifier'];
}
if(isset($_POST['password']))
{
  $password = $_POST['password'];
}
if(isset($_SESSION['userId']))
{
  session_destroy();
  ?>

Logging out...
<script type = "text/javascript">
window.onload(LOGINreload());
</script>
  <?php
  //logout
  exit;
}
if(isset($identifier) && isset($password) && $identifier != '' && $password != '')
{
  //start login
  $stmt = $db->prepare("SELECT id, `password`, `permissions` FROM user WHERE identifier LIKE ?");
  $stmt->execute(array($identifier));
  $logRow = $stmt->fetch();
  if($logRow && $logRow['id'] != '')
  {
    if($logRow['password'] == md5($password))
    {
    $_SESSION['userId'] = $logRow['id']; //save userid
    //save user permissions
    $avPermissions = explode(",,", $logRow['permissions']);
    $avPermissions = str_replace(",","", $avPermissions);
    $_SESSION['userPermissions'] = $avPermissions;
    
    $loginError = "<p style='color:green'>Login successful! Please wait...</p>";
    ?>
    <script type = "text/javascript">
window.onload(LOGINreload());
</script>
    <?php
    }
    else
    {
      //wrong password
      $loginError = "<p style='color:red'>SLogin failed. Please try again or ask at the service desk. Thank you!</p>";
    }
  }
  else
  {
    //user not foumd
    $loginError = "<p style='color:red'>Login failed. Please try again or ask at the service desk. Thank you!</p>";
  }
}
else
{
  $loginError = "";
}

?>

<script>
function LOGINlogin(element)
{
    
    email = document.getElementsByClassName('identifierField')[0].value;
    password = document.getElementsByClassName('passwordField')[0].value;
    var postArray = {'identifier':email, 'password':password};

    goTo(element, "modules/start.php?id=13", postArray);
}
</script>
<div class="d-flex h-100 w-100 align-items-center justify-content-center">
    

<form class="LoginForm" name="LoginForm" onSubmit="LOGINlogin(this)" action="javascript:void(0);">

  <?php if ($loginError != "")
  {

?>
<div class="form-outline mb-3">
    <?php echo $loginError; ?>
  </div>
  <?php
  }
  ?>
  <!-- Email input -->
  <div class="form-outline mb-3">
    <input type="input" id="form2Example1" class="form-control identifierField" />
    <label class="form-label" for="form2Example1">Identifier</label>
  </div>

  <!-- Password input -->
  <div class="form-outline mb-3">
    <input type="password" id="form2Example2" class="form-control passwordField" />
    <label class="form-label" for="form2Example2">Password</label>
  </div>


  <!-- Submit button -->
  <button type="submit" class="btn btn-primary btn-block mb-4">Log in</button>


</form>
      </div>