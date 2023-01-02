<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once("../../connect.php");

if(!in_array(5, $_SESSION['userPermissions']))
{
echo "No Permissions!";
exit;
}

if(isset($_GET['memberId']))
{
    $memberId = $_GET['memberId'];
}

    //got permissions to add member

    //get posted data
    if(isset($_POST['lastName']) && $_POST['lastName'] != '')
    {
        $lastName = $_POST['lastName'];
    }
    else
    {
        echo "Please enter last name";
        exit;
    }
    if(isset($_POST['firstName']) && $_POST['firstName'] != '')
    {
        $firstName = $_POST['firstName'];
    }
    else
    {
        $firstName = "";
    }

    if(isset($memberId) || isset($_POST['password']) && $_POST['password'] != '')
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
        if(!isset($memberId) || $memberId == "")
        {

        
        
        echo "Please enter password!";
        exit;
    }
    }
    
    $competitions = [];
    if(isset($_POST['competitions']))
    {
        $competitions = $_POST['competitions'];
    }

    $disciplines = [];
    if(isset($_POST['disciplines']))
    {
        $disciplines = $_POST['disciplines'];
    }

    
    $criteria= [];
    if(isset($_POST['criteria']))
    {
        $criteria = $_POST['criteria'];
    }

    //check if identifier already exists in this environment
    $stmt = $db->prepare("SELECT id FROM user WHERE environmentId LIKE ? AND identifier LIKE ?");
      $stmt->execute(array($_SESSION['environmentId'], $identifier));
      $checkRow = $stmt->fetch();

      if(isset($checkRow['id']) && $checkRow['id'] != '')
      {
          if(!isset($memberId) || $checkRow['id'] != $memberId)
          { 
          echo "A member with this identifier already exists.";
          exit;
          }
      }
  
          //Add new member or Update member
          $permissionString = "";
          for($i = 0; $i<count($permissions); $i++)
          {
              $permissionString = $permissionString.",".$permissions[$i].",";
          }
          if(isset($memberId) && $memberId != "")
          {
           
              if($password != '')
              {
                $eintrag = $db->prepare("UPDATE user SET firstName=?, lastName=?, identifier=?, `password`=?, `permissions`=? WHERE id LIKE ?");
                $eintrag->execute(array($firstName, $lastName, $identifier, md5($password), $permissionString, $memberId)) ;      
              }
              else
              {
                $eintrag = $db->prepare("UPDATE user SET firstName=?, lastName=?, identifier=?, `permissions`=? WHERE id LIKE ?");
                $eintrag->execute(array($firstName, $lastName, $identifier, $permissionString, $memberId)) ;   
              }
          }
          else
          {
                   $eintrag = $db->prepare("INSERT INTO user (environmentId, firstName, lastName, identifier, `password`, `permissions`) VALUES (?, ?, ?, ?, ?, ?)");
  $eintrag->execute(array($_SESSION['environmentId'], $firstName, $lastName, $identifier, md5($password), $permissionString)) ;     

          }

  if($eintrag)
  { 
      if(isset($memberId) && $memberId != "")
      {
          $last_id = $memberId;
          //remove id from all competitions and disciplines and judging
          $userIdInCommas = ",".$last_id.",";
          $searchUserId ="%$userIdInCommas%";
          $stmt = $db->prepare("UPDATE`competitions` SET associatedMembers = REPLACE(associatedMembers, ?, '') WHERE environmentId LIKE ? AND associatedMembers LIKE ?");
          $stmt->execute(array($userIdInCommas, $_SESSION['environmentId'],  $searchUserId));   
          $stmt = $db->prepare("UPDATE`disciplines` SET associatedMembers = REPLACE(associatedMembers, ?, '') WHERE associatedMembers LIKE ?");
          $stmt->execute(array($userIdInCommas,  $searchUserId));  
          $stmt = $db->prepare("DELETE FROM judging WHERE userId LIKE ?");
          $stmt->execute(array($last_id)); 
        }
      else
      {
      $last_id = $db->lastInsertId();
      }
      //insert id into associated competitions
      $last_idInComma = ','.$last_id.',';
      for($i = 0; $i<count($competitions); $i++)
      {
          $stmt = $db->prepare("UPDATE competitions SET associatedMembers = concat(associatedMembers, ?) WHERE id = ?");
      $stmt->execute(array($last_idInComma,  $competitions[$i]));   
      if(!$stmt)
      {
        echo "Something went wrong";
        exit;   
      }    
      }
      for($i = 0; $i<count($disciplines); $i++)
      {
          $stmt = $db->prepare("UPDATE disciplines SET associatedMembers = concat(associatedMembers, ?) WHERE id = ?");
      $stmt->execute(array($last_idInComma,  $disciplines[$i]));
      if(!$stmt)
      {
        echo "Something went wrong";
        exit;   
      }   
      if(in_array(7, $permissions))
      {   
          for($j = 0; $j<count($criteria); $j++)
          {
              $criteriaData = explode("~", $criteria[$j]);
              if($criteriaData[0] == $disciplines[$i])
              {
                $stmt = $db->prepare("INSERT INTO judging (userId, disciplineId, criteria, `range`) VALUES (?,?,?,?)");
                $stmt->execute(array($last_id,  $disciplines[$i], $criteriaData[1], $criteriaData[2]));
              }

          }
      }
      }
echo "success";
      }
    else
    {
        echo "Something went wrong";
        exit;
    }
