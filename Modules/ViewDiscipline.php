
<?php include("../includeSafety.php"); ?>
<?php 

if(isset( $_GET['disId']))
{
    $disId = $_GET['disId'];
} 
if(isset( $_GET['compId']))
{
    $compId = $_GET['compId'];
} 
$userId = $_SESSION['userId'];
$userIdInCommas = ",".$userId.",";
$searchUserId ="%$userIdInCommas%";
$stmt = $db->prepare("SELECT disciplines.* FROM disciplines LEFT JOIN competitions ON competitions.id LIKE disciplines.compId WHERE disciplines.id LIKE ? AND competitions.associatedMembers LIKE ?");
      $stmt->execute(array($disId, $searchUserId));
      $disRow = $stmt->fetch();
    if($disRow['id'] != '')
    {
        //permitted to show
          ?>
          <title class="title"><?php echo $disRow['title'] ?></title>
          <div class="container">
  <div class="row">


     <div onclick="createPanel('modules/start.php?id=7&disId=<?php echo $disRow['id']; ?>')" class="btn btn-primary col-sm m-1">
         <i class="fas fa-chess-board fa-lg"></i><br>
        <input class="btn btn-primary" type="submit" value="View Start List"></input>
      </div>
      <div onclick="createPanel('modules/start.php?id=8&disId=<?php echo $disRow['id']; ?>')" class="btn btn-primary col-sm m-1">
         <i class="fas fa-chess-board fa-lg"></i><br>
        <input class="btn btn-primary" type="submit" value="View Scoreboard"></input>
      </div>
  
  </div>
          </div>
          <?php } ?>