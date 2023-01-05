<?php
/**
* This module helper script retrieves all the possible permissions and their titles from the database in order to check them by title 
*/

$permissionNames =  [];
$stmt = $db->prepare("SELECT * FROM `permissions` ORDER BY title");
$stmt->execute();
while($permRow = $stmt->fetch(PDO::FETCH_ASSOC)) //Get all PermissionNames
{
  $permissionNames[$permRow['id']] = $permRow['title'];
}

$permissionPresets =  [];
$stmt = $db->prepare("SELECT * FROM `permissionpresets` ORDER BY title");
$stmt->execute();
while($permRow = $stmt->fetch(PDO::FETCH_ASSOC)) //Get all PermissionPresets
{
    $permissionPresetIds = explode(",,",$permRow['value']);
    for($i = 0; $i<count($permissionPresetIds); $i++)
    {
        $permissionPresetIds[$i] = str_replace(",","",$permissionPresetIds[$i]);
    }
  $permissionPresets[$permRow['title']] = $permissionPresetIds;
}
