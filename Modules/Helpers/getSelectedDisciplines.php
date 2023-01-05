<?php

/**
 * This module helper script retrieves the disciplines available for the selected competitions
 */

if (!isset($addMemberRootPage) || !$addMemberRootPage) {
  include_once("../../connect.php");
  if (isset($_GET['memberId'])) {
    $memberId = $_GET['memberId'];
  }
  if (isset($_GET['disId'])) {
    $disId = $_GET['disId'];
  }
  if (isset($_GET['compId'])) {
    $compId = $_GET['compId'];
  }
  if (isset($_POST['selectedComps'])) {
    $selectedComps = $_POST['selectedComps'];
  } else {
    $selectedComps = [];
  }
}
if (count($selectedComps) > 0) {
  $in  = str_repeat('?,', count($selectedComps) - 1) . '?';
  $stmt = $db->prepare("SELECT * FROM disciplines WHERE compId in($in)");
  $stmt->execute($selectedComps);
  while ($disRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $disIsSelected = "";
    $selectedDis = array();
    if (isset($memberId) && $memberId != "") {
      if (strpos($disRow['associatedMembers'], ',' . $memberRow['id'] . ',') != false) //checking if member is associated with next competition in loop
      {
        $disIsSelected = "SELECTED";
        $selectedDis[] = $disRow['id'];
      }
    }
    if (isset($disId) && $disId != "") {
      if ($disRow['id'] == $disId) //checking given discipline is next discipline in loop
      {
        $disIsSelected = "SELECTED";
        $selectedDis[] = $disRow['id'];
      }
    }
?>
    <option value="<?php echo $disRow['id']; ?>" <?php echo $disIsSelected; ?>><?php echo $disRow['title']; ?></option>
<?php
  }
}
?>