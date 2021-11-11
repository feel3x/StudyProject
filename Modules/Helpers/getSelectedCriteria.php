<?php
if(!$addMemberRootPage)
{
include("../../connect.php");
if(isset($_GET['memberId']))
{
    $memberId = $_GET['memberId'];
}
if(isset($_GET['disId']))
{
    $disId = $_GET['disId'];
}
if(isset($_GET['compId']))
{
    $compId = $_GET['compId'];
}
if(isset($_POST['selectedDisciplines']))
{
    $selectedDis = $_POST['selectedDisciplines'];
}
}
          if(count($selectedDis)>0)
          {
      $in  = str_repeat('?,', count($selectedDis) - 1) . '?';
$stmt = $db->prepare("SELECT * FROM disciplines WHERE id in($in)");
      $stmt->execute($selectedDis);
    while($selDisRow = $stmt->fetch(PDO::FETCH_ASSOC)) //Get all Disciplines selected
    {    
        if(isset($memberId))
        {
        $selectedCriteria = array();
      $stmt = $db->prepare("SELECT * FROM judging WHERE disciplineId LIKE ? AND userId LIKE ?");
      $stmt->execute(array($selDisRow['id'], $memberId));
      while($judgeRow = $stmt->fetch(PDO::FETCH_ASSOC)) //Get all judging criteria associated with the judge and discipline
      {
        $selectedCriteria[] = $judgeRow['criteria'];
      }
    }
      $disciplineCriteria = explode(",",$selDisRow['criteria']);
      for($i = 0; $i<count($disciplineCriteria); $i++)
      {
      $criteriaIsSelected = "";
        if(in_array($disciplineCriteria, $selectedCriteria)) //checking if member is associated with next competition in loop
      {
         $criteriaIsSelected = "SELECTED";
      }
      ?>
          <option value="<?php echo $selDisRow['id']; ?>-<?php echo $disciplineCriteria[$i]; ?>" <?php echo $criteriaIsSelected; ?>><?php echo $selDisRow['title']; ?> - <?php echo $disciplineCriteria[$i]; ?></option>
      <?php
    }  
  }       
  }
    ?>