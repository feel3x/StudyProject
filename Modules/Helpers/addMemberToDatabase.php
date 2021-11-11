<?php 

$error = [];

if(!in_array(7, $_SESSION['userPermissions']))
{
echo "No Permissions!";
exit;
}

    //got permissions to add member

    //get posted data
    if(isset($_POST['formalName']) && $_POST['formalName'] != '')
    {
        $formalName = $_POST['formalName'];
    }
    else
    {
        echo "Please enter name";
        exit;
    }

    if(isset($_POST['password']) && $_POST['password'] != '')
    {
        $password = $_POST['password'];
    }
    else
    {
        echo "Please enter password!";
        exit;
    }

    if(isset($_POST['identifier']) && $_POST['identifier'] != '')
    {
        $identifier = $_POST['identifier'];
    }
    else
    {
        echo "Please enter identifier!";
        exit;
    }

    
    if(isset($_POST['permissions']) && count($_POST['permissions']) > 0)
    {
        $permissions = $_POST['permissions'];
    }
    else
    {
        echo "Please set permissions!";
        exit;
    }

    
    if(isset($_POST['password']) && $_POST['password'] != '')
    {
        $password = $_POST['password'];
    }
    else
    {
        echo "Please enter password!";
        exit;
    }
    
    $competitions;
    if(isset($_POST['competitions']))
    {
        $competitions = $_POST['competitions'];
    }

    $disciplines;
    if(isset($_POST['disciplines']))
    {
        $disciplines = $_POST['disciplines'];
    }

    
    $criteria;
    if(isset($_POST['criteria']))
    {
        $criteria = $_POST['criteria'];
    }

    //check if identifier already exists in this environment
    $stmt = $db->prepare("SELECT id FROM user WHERE environmentId LIKE ? AND identifier LIKE ?");
      $stmt->execute(array($_SESSION['environmentId'], $identifier));
      $checkRow = $stmt->fetch();

      if($checkRow['id'] != '')
      {
          echo "A member with this identifier already exists.";
          exit;
      }
      else
      {
          //Add new member
          $permissionString;
          for($i = 0; $i<count($permissions); $i++)
          {
              $permissionString = $permissionString.",".$permissions[$i].",";
          }
          $eintrag = $db->prepare("INSERT INTO user (environmentId, formalName, identifier, `password`, `permissions`) VALUES (?, ?, ?, ?, ?)");
  $eintrag->execute(array($_SESSION['environmentId'], $formalName, $identifier, md5($password), $permissionString)) ;
  if($eintrag)
  { 
      $last_id = $db->insert_id;
      //insert id into associated competitions
      $last_id = ','.$last_id.',';
      for($i = 0; $i<count($competitions); $i++)
      {
          $stmt = $db->prepare("UPDATE competitions SET associatedMembers = concat(associatedMembers, ?) WHERE id = ?");
      $stmt->execute(array($last_id,  $competitions[$i]));   
      if(!$stmt)
      {
        echo "Something went wrong";
        exit;   
      }    
      }
      for($i = 0; $i<count($disciplines); $i++)
      {
          $stmt = $db->prepare("UPDATE disciplines SET associatedMembers = concat(associatedMembers, ?) WHERE id = ?");
      $stmt->execute(array($last_id,  $disciplines[$i]));
      if(!$stmt)
      {
        echo "Something went wrong";
        exit;   
      }      
      }

      }
    else
    {
        echo "Something went wrong";
        exit;
    }
}

?>