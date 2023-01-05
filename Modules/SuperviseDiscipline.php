<?php 
/**
* This Module gives the user with the necessary permissions the ability to supervise a discipline.
* The user can start/stop the discipline.
* Only if the discipline is started are the judges able to judge athletes.
* The user can swtich between competitors who are currently being judged.
* Only the selected competitor can be judged by the judges.
* The order of competitors is tied to the start list for easier access.
* The user recieves information about the scores and judges who are still missing their scores.
*/

include("../includeSafety.php"); 
if(isset($_GET['disId']))
{
    $disId = $_GET['disId'];
}

if(in_array(11, $_SESSION['userPermissions']))
{
    $stmt = $db->prepare("SELECT disciplines.id FROM disciplines WHERE disciplines.id LIKE ?");
    $stmt->execute(array($disId));
}
else
{
    $userId = $_SESSION['userId'];
$userIdInCommas = ",".$userId.",";
$searchUserId ="%$userIdInCommas%";
$stmt = $db->prepare("SELECT disciplines.id FROM disciplines WHERE disciplines.id LIKE ? AND associatedMembers LIKE ?");
      $stmt->execute(array($disId, $searchUserId));
}
      $disRow = $stmt->fetch();
      if($disRow['id'] == null)
      {
        echo "Something went wrong";
        exit;
      }

function getScoreString($ruleset)
{
   
preg_match_all("/([a-z]*?\([a-z]*?\))/", $ruleset, $matches);
$matches = $matches[0];
for($i = 0; $i < count($matches); $i++)
{
    $data = explode("(", $matches[$i]);
        $replacement = $data[0]."(CASE WHEN criteria = '".str_replace(")","",$data[1])."' THEN value ELSE 0 end)";
       $ruleset = str_replace($matches[$i], $replacement, $ruleset);
}
return $ruleset;
}

function updateDisInfo()
{
  $stmt = $GLOBALS['db']->prepare("SELECT * FROM disciplines LEFT JOIN executediscipline ON disciplines.id LIKE executediscipline.disciplineId WHERE disciplines.id LIKE ?");
  $stmt->execute(array($GLOBALS['disId']));
  $GLOBALS['disRow'] = $stmt->fetch();
}

updateDisInfo();

$startList = [];
      $competitors = str_replace(',', '', explode(',,', $disRow['associatedMembers']));
      $in  = str_repeat('?,', count($competitors) - 1) . '?';
      $newSQL ="";
      switch(explode(":",$disRow['startListSort'])[0])
      {
        case "random":
          $orderBy = "rand(1)";
          break;
          case "scoreOfId":
            $stmt = $db->prepare("SELECT criteriaRuleset FROM disciplines WHERE id LIKE ?");
            $sortData = explode(":",$disRow['startListSort']);
            $stmt->execute(array($sortData[1]));
            $tempRow = $stmt->fetch();
            $newSQL = "SELECT *, (SELECT ".getScoreString($tempRow['criteriaRuleset'])." 
            FROM scores WHERE disciplineId LIKE ? AND competitorId LIKE user.id GROUP BY competitorId) AS score FROM user WHERE id in($in) AND `permissions` LIKE '%,9,%' ORDER BY score ".$sortData[2].", lastName ";
            break;
        default:
        $orderBy = $disRow['startListSort'];
        break;
      }
      if($newSQL == "")
      {
$stmt = $db->prepare("SELECT id FROM user WHERE id in($in) AND `permissions` LIKE '%,9,%' ORDER BY $orderBy");
      $stmt->execute($competitors);
      }
      else
      {
        $stmt = $db->prepare($newSQL);
      $stmt->execute(array_merge(array(explode(":",$disRow['startListSort'])[1]), $competitors));
      }
      while($startRow = $stmt->fetch(PDO::FETCH_ASSOC))
      {
        $startList[] = $startRow['id'];
      }
      $supposedCurrentId = 0;
    for($i = 0; $i< count($startList); $i++)
    {
      
      $stmt = $db->prepare("SELECT scores.id AS testId, judging.userId AS judge, judging.criteria, scores.value AS score FROM `judging` LEFT JOIN scores ON scores.disciplineId LIKE judging.disciplineId AND scores.judgeId LIKE judging.userId AND judging.criteria LIKE scores.criteria AND competitorId LIKE ? WHERE judging.disciplineId LIKE ?");
      $stmt->execute(array($startList[$i], $disId));
      while($startRow = $stmt->fetch(PDO::FETCH_ASSOC))
      {
        if($startRow['testId'] == null)
        {
          $supposedCurrentId = $startList[$i];
          break;
        }
      } 
      if($supposedCurrentId != 0)
      {
        break;
      }
    }
    if($supposedCurrentId == 0)
    {
      $supposedCurrentId = $startList[0];
    }

    
if(isset($_POST['startDisId']) && $_POST['startDisId'] != "")
{
  $startDisId = $_POST['startDisId'];
  //check if entry already exists
  $stmt = $db->prepare("SELECT id FROM executediscipline WHERE disciplineid LIKE ?");
      $stmt->execute(array($startDisId));
      $tempRow = $stmt->fetch();
      if($tempRow['id'] == null)
      {
        //create new entry
        $eintrag = $db->prepare("INSERT INTO executediscipline (disciplineId, currentAthlete, currentRound) VALUES (?, ?, ?)");
        $eintrag->execute(array($startDisId, $supposedCurrentId, 1)) ;  
      }
updateDisInfo();
}
$goToIdSQL = "";
$postArray = [];
if(isset($_POST['goToId']))
{
$postArray[] = $_POST['goToId'];
$goToIdSQL = " currentAthlete = ? ";
}
$goToRoundSQL = "";
if(isset($_POST['goToRound']))
{
  $postArray[] = $_POST['goToRound'];
  if($postArray > 1)
  {
    $goToRoundSQL = ", currentRound = ? ";
  }
  else
  {
  $goToRoundSQL = " currentRound = ? ";
  }
}
if(count($postArray) > 0)
{
  $eintrag = $db->prepare("UPDATE executediscipline SET $goToIdSQL $goToRoundSQL WHERE disciplineId LIKE ?");
  $eintrag->execute(array_merge($postArray, array($disId))) ;  
  updateDisInfo();
}

if(isset($_POST['changeState']))
{
  $eintrag = $db->prepare("UPDATE executediscipline SET `state` = ? WHERE disciplineId LIKE ?");
  $eintrag->execute(array($disRow['state']=='1'?0:1, $disId)) ;  
  updateDisInfo(); 
}
       
        ?>
<title class="title"><?php echo $disRow['title']; ?> - Supervise Discipline</title>





<?php if($disRow['state'] === null)
{
  //competition has not started
  ?>
     <div class="d-flex h-100 align-items-center justify-content-center">
        <div class="p-2 bd-highlight col-example"><button onClick="refreshPanel(getPanel(this).id, {startDisId:<?php echo $disId;?>}, true)" class="btn btn-primary">Start Discipline</button></div>
      </div>
<?php





}
else
{
  $panelId = isset($_GET['panelId'])?$_GET['panelId']:null;
  ?>
<script>
function SUPERVISEDISCIPLINErefresh<?php echo $panelId; ?>()
{
    refreshingContent["<?php echo $panelId; ?>"] = setTimeout(function() { refreshPanel("<?php echo $panelId; ?>"); }, 10000);
}

   
SUPERVISEDISCIPLINErefresh<?php echo $panelId; ?>();
</script>

<?php
$userNames = [];
$in  = str_repeat('?,', count($startList) - 1) . '?';
$stmt = $db->prepare("SELECT lastName, firstName, id FROM user WHERE id IN ($in)");
      $stmt->execute($startList);
      while($tempRow = $stmt->fetch(PDO::FETCH_ASSOC))
      {
$userNames[$tempRow['id']] = $tempRow['firstName']." ".$tempRow['lastName'];
      }

      ?>



<div id="thisPage" class="container sectionToPrint">     
<div class="container-fluid">
  <div class="row justify-content-md-center p-2">  
      <div class="row d-inline-flex p-2 border border-primary">
        <div class="col"><button class="btn btn-primary" onClick="refreshPanel(getPanel(this).id, {changeState:<?php echo $disRow['state']==1?0:1; ?>}, true)"><i class="fa fa-<?php echo $disRow['state']==1?"play":"pause"; ?> fa-lg"></i></button></div>
        <div class="col">Current Round: <b><?php echo $disRow['currentRound']; ?></b></div>
      </div>
  </div>
  <div class="row justify-content-md-center p-2">
    <?php 
    $indexOfCurrentAthlete = array_search($disRow['currentAthlete'], $startList);
    if($indexOfCurrentAthlete>0)
    {
      ?>
 <div class="col-sm-2 m-2"><button class="btn btn-primary p-2" onClick="refreshPanel(getPanel(this).id, {goToId:<?php echo $startList[($indexOfCurrentAthlete-1)]; ?>}, true)"><i class="fa fa-chevron-left fa-lg"></i> <?php echo $userNames[$startList[($indexOfCurrentAthlete-1)]]; ?></button></div>
      <?php
    }
    else if($disRow['currentRound'] > 1)
    {
      ?>
      
       <div class="col-sm-2 m-2"><button class="btn btn-primary p-2" onClick="refreshPanel(getPanel(this).id, {goToId:<?php echo $startList[(count($startList)-1)]; ?>, goToRound:<?php echo ($disRow['currentRound'] -1); ?>}, true)"><i class="fa fa-chevron-left fa-lg"></i> <?php echo $userNames[$startList[(count($startList)-1)]]; ?> - Round <?php echo ($disRow['currentRound'] - 1); ?></button></div>
  <?php
    }
    else
    {
      ?>
            <div class="col-sm-2 m-2"><button disabled class="btn btn-primary p-2"><i class="fa fa-chevron-left fa-lg"></i></button></div>

      <?php
    }
    ?>

    <div class="col-8-md  m-2">
      <table class="table-dark rounded-top">
        <thead>
        <tr>
          <th colspan="2" class="p-2 bg-primary text-center rounded-top"><?php echo "#".(array_search($disRow['currentAthlete'], $startList)+1)." ".$userNames[$disRow['currentAthlete']]; ?></th>
        </tr> 
      </thead>
    
      <?php
        $stmt = $db->prepare("SELECT scores.id AS testId, judging.userId AS judge, judging.criteria, scores.value AS score, concat(user.firstName, ' ', user.lastName) AS judgeName FROM `judging` LEFT JOIN scores ON scores.disciplineId LIKE judging.disciplineId AND scores.judgeId LIKE judging.userId AND judging.criteria LIKE scores.criteria AND competitorId LIKE ?  AND `round` LIKE ? LEFT JOIN user ON user.id LIKE judging.userId WHERE judging.disciplineId LIKE ?");
        $stmt->execute(array($disRow['currentAthlete'], $disRow['currentRound'], $disId));
        while($judgeRow = $stmt->fetch(PDO::FETCH_ASSOC))
        {
          ?>
            <tr>
          <td class="p-1"><?php echo $judgeRow['judgeName']." - ".$judgeRow['criteria'];?></td>
          <td class="p-1 bg-<?php echo $judgeRow['score']===null?"danger":"success"; ?>"><?php echo $judgeRow['score']===null?"Missing":$judgeRow['score']; ?></td>
        </tr>
         
      <?php
        }
?>       </table> 
    </div>


    <?php 
    $indexOfCurrentAthlete = array_search($disRow['currentAthlete'], $startList);
    if($indexOfCurrentAthlete<count($startList)-1)
    {
      ?>
 <div class="col-sm-2 m-2"><button class="btn btn-primary p-2" onClick="refreshPanel(getPanel(this).id, {goToId:<?php echo $startList[($indexOfCurrentAthlete+1)]; ?>}, true)"><?php echo $userNames[$startList[($indexOfCurrentAthlete+1)]]; ?> <i class="fa fa-chevron-right fa-lg"></i></button></div>
      <?php
    }
    else
    if($disRow['currentRound'] < $disRow['rounds'])
    {
      ?>
      <div class="col-sm-2 m-2"><button class="btn btn-primary p-2" onClick="refreshPanel(getPanel(this).id, {goToId:<?php echo $startList[0]; ?>, goToRound:<?php echo ($disRow['currentRound'] + 1); ?>}, true)"><?php echo $userNames[$startList[0]]; ?> - Round <?php echo ($disRow['currentRound'] + 1); ?> <i class="fa fa-chevron-right fa-lg"></i></button></div>
 <?php
    }
    else
    {
      ?>
            <div class="col-sm-2 m-2"><button disabled class="btn btn-primary p-2"><i class="fa fa-chevron-right fa-lg"></i></button></div>

      <?php
    }
    ?>
  </div>

</div>
<?php   
    }
      ?>
</div>