<?php

/**
 * This module helper script retrieves all the criteria that is available for judging given the selected disciplines and comeptitions.
 */

$selectedDis = array();
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
  if (isset($_POST['selectedDisciplines'])) {
    $selectedDis = $_POST['selectedDisciplines'];
  }
}
if (count($selectedDis) > 0) {
  $in  = str_repeat('?,', count($selectedDis) - 1) . '?';
  $stmt = $db->prepare("SELECT * FROM disciplines WHERE id in($in)");
  $stmt->execute($selectedDis);
  while ($selDisRow = $stmt->fetch(PDO::FETCH_ASSOC)) //Get all Disciplines selected
  {
    $selectedCriteria = array();

    if (isset($memberId) && $memberId != "") {
      $stmt2 = $db->prepare("SELECT * FROM judging WHERE disciplineId LIKE ? AND userId LIKE ?");
      $stmt2->execute(array($selDisRow['id'], $memberId));
      while ($judgeRow = $stmt2->fetch(PDO::FETCH_ASSOC)) //Get all judging criteria associated with the judge and discipline
      {
        $selectedCriteria[] = $judgeRow['criteria'];
      }
    }
    $disciplineCriteria = explode(",", $selDisRow['criteria']);
    for ($i = 0; $i < count($disciplineCriteria); $i++) {
      $criteriaIsSelected = "";
      if (in_array($disciplineCriteria, $selectedCriteria)) //checking if member is associated with next competition in loop
      {
        $criteriaIsSelected = "SELECTED";
      }
?>
      <option value="<?php echo $selDisRow['id']; ?>~<?php echo $disciplineCriteria[$i]; ?>" <?php echo $criteriaIsSelected; ?>><?php echo $selDisRow['title']; ?> - <?php echo $disciplineCriteria[$i]; ?></option>
<?php
    }
  }
}
?>