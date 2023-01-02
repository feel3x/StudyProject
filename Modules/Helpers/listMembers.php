<?php 
ini_set('display_errors', 1);
include_once("../../connect.php");
include_once("PermissionNames.php");
$error = [];

if(!in_array(8, $_SESSION['userPermissions']))
{
echo "No Permissions!";
exit;
}

    //got permissions to manage member
$in = "";
    //get posted data
    $permissions = [];
    if(isset($_POST['permissions']) && count($_POST['permissions']) > 0)
    {
        $permissions = $_POST['permissions'];
    }
    if(count($permissions) > 0)
    {
    for($i = 0; $i<count($permissions); $i++)
    {
        $tempPermit = ",".$permissions[$i].",";
        $permissions[$i] = "%$tempPermit%";
    }
    $customPerms = "";
    if($_POST['presets'] != "custom")
    {
        $customPerms = " AND (cast(((CHAR_LENGTH(`permissions`) - CHAR_LENGTH(REPLACE(`permissions`, ',,', ''))) / 2) AS unsigned) + 1) LIKE " .count($permissions). " ";
    }
    $in  = str_repeat(' AND `permissions` LIKE ? ', count($permissions) - 1) . ' AND `permissions` LIKE ? ';
    $in = $in."".$customPerms;
}
    
$memberIdsInComps = [];  
    $competitions = [];
    if(isset($_POST['competitions']) && $_POST['competitions'] != "")
    {
        $competitions = $_POST['competitions'];
    }
    if(count($competitions) > 0)
    {
    $inComp  = str_repeat('?,', count($competitions) - 1) . '?';
    $stmt = $db->prepare("SELECT associatedMembers FROM competitions WHERE id IN ($inComp)");
    $stmt->execute($competitions);
    while($compRow = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        $arrayOfIds = explode(",,", $compRow['associatedMembers']);
        for($i = 0; $i<count($arrayOfIds); $i++)
        {
            $memberIdsInComps[] = str_replace(",", "", $arrayOfIds[$i]);
        }
    }
}

    $disciplines = [];
    if(isset($_POST['disciplines']) && $_POST['disciplines'] != "")
    {
        $disciplines = $_POST['disciplines'];
    }
    if(count($disciplines) > 0)
    {
        $memberIdsInComps = []; 
    $inComp  = str_repeat('?,', count($disciplines) - 1) . '?';
    $stmt = $db->prepare("SELECT associatedMembers FROM disciplines WHERE id IN ($inComp)");
    $stmt->execute($disciplines);
    while($compRow = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        $arrayOfIds = explode(",,", $compRow['associatedMembers']);
        for($i = 0; $i<count($arrayOfIds); $i++)
        {
            $memberIdsInComps[] = str_replace(",", "", $arrayOfIds[$i]);
        }
    }
}

    
    $criteria= [];
    $judgeIds = [];
    if(isset($_POST['criteria']) && $_POST['criteria'] != "")
    {
        $criteria = $_POST['criteria'];
    }
    if(count($criteria) >0)
    {
        $judgingDiciplines = [];
        for($i = 0; $i < count($criteria); $i++)
        {
            $criteriaValues = explode("-", $criteria[$i]);
            $judgingDiciplines[$criteriaValues[0]][] = $criteriaValues[1];
        }
    $inCrit = "";
    $inCritArray = [];
        foreach($judgingDiciplines as $key => $val) {
            $tempInCrit  = str_repeat('?,', count($val) - 1) . '?';
            if($inCrit == "")
            {
                $inCrit = "criteria IN ($tempInCrit) AND disciplineId LIKE ?";
            }
            else
            {
                $inCrit = $inCrit." OR criteria IN ($tempInCrit) AND disciplineId LIKE ?";
            }
           
            $inCritArray = array_merge($inCritArray, $val, array($key));
        }
    
    $stmt = $db->prepare("SELECT userId FROM judging WHERE $inCrit");
    $stmt->execute($inCritArray);
    while($judgeRow = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        $judgeIds[] = $judgeRow['userId'];
    }
}


    $scoreCriteria= [];
    if(isset($_POST['scoreCriteria']) && $_POST['scoreCriteria'] != "")
    {
        $scoreCriteria = $_POST['scoreCriteria'];
    }
   

if(count($memberIdsInComps) > 0)
{
    $inTemp = str_repeat('?,', count($memberIdsInComps) - 1) . '?';
    $in = $in." AND id IN($inTemp)";
}
if(count($criteria)>0)
{
    if(count($judgeIds)>0)
    {
          $inTemp = str_repeat('?,', count($judgeIds) - 1) . '?';
            $in = $in." AND id IN($inTemp)";
}
else
{
    $in = $in." AND id IN(0)";
}
}

?>
<div style="display:none" class="btn btn-dark sticky-top memberActionDiv"><?php if(checkPermission("Delete Members")){ ?><button onClick="MANAGEMEMBERSdeleteMemberIds(this)" class="btn btn-danger m-2">Delete</button><?php } ?> <?php if(checkPermission("Move Members")){ ?><button onClick="MANAGEMEMBERSmoveMemberIds(this)" class="btn btn-primary m-2">Copy To</button><?php } ?> </div>
<form class="memberIdForm" name="memberIdForm" action="javascript:void(0);">
<table class="table table-striped table-dark">

<?php
    $stmt = $db->prepare("SELECT id, concat(firstName, ' ', lastName) AS `Name`, identifier AS `Identifier`, `permissions` AS `Roles` 
    FROM user 
    WHERE environmentId LIKE ? $in");
      $stmt->execute(array_merge(array($_SESSION['environmentId']), $permissions, $memberIdsInComps, $judgeIds));
      $iteration = 0;
      $tableFields = [];
     $numCols = $stmt->columnCount();
      while($memberRow = $stmt->fetch(PDO::FETCH_ASSOC))
      {
         
          if($iteration == 0)
          {
              echo "<thead class='thead-light'>";
              echo "<tr>";
              echo "<th> </th>";
              $fieldCounter = 1;
              for ( $i = 0; $i < $numCols; $i++ ) {
                $tableFieldName = $stmt->getColumnMeta($i)['name'];
                if($tableFieldName != "id")
                {
                $tableFields[] = $tableFieldName;
                echo "<th onClick='sortTable(this, \"".($fieldCounter)."\")'>".$tableFieldName."</th>";
                $fieldCounter++;
                }
              }
               
              
            echo "</tr>";   
            echo "</thead>"; 
            echo "<tbody>";
          }
          echo "<tr>";
          ?>
          <td>
              <input onchange="MANAGEMEMEBERScheckCheckedBoxes(this)" type="checkbox" class="memberCheckbox" name="memberIdSelected[]" value="<?php echo $memberRow['id']; ?>"></input></td>
          <?php
          for ( $i = 0; $i < count($tableFields); $i++ ) {
              if($tableFields[$i] == "Roles")
              {
                  $presetsOfMember = "";
                $permissionPresetIds = explode(",,",$memberRow['Roles']);
                for($j = 0; $j<count($permissionPresetIds); $j++)
                {
                    $permissionPresetIds[$j] = str_replace(",","",$permissionPresetIds[$j]);
                }
                foreach($permissionPresets as $key => $val) {
                   if(count($val) == count($permissionPresetIds))
                   {
                       $isGivenPreset = true;
                       for($j = 0; $j<count($permissionPresetIds); $j++)
                       {
                           if(!in_array($permissionPresetIds[$j], $val))
                           {
                               $isGivenPreset = false;
                               break;
                           }
                       }
                       if($isGivenPreset)
                       $presetsOfMember = $key;
                   }
                }
                if($presetsOfMember == "")
                {
                $presetsOfMember = "Custom Permissions";
                }
                ?>
                <td onclick="createPanel('modules/start.php?id=10&memberId=<?php echo $memberRow['id']; ?>')"><?php echo $presetsOfMember; ?></td> 
               <?php
              }
              else
              {

              
                   ?>
                 <td onclick="createPanel('modules/start.php?id=10&memberId=<?php echo $memberRow['id']; ?>')"><?php echo $memberRow[$tableFields[$i]]; ?></td>      
            <?php  
              }
          }   
          echo "</tr>"; 

$iteration++;
      }

?>

</tbody>
</table>
</form>