
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

if(in_array(11, $_SESSION['userPermissions']))
{
  $stmt = $db->prepare("SELECT disciplines.* FROM disciplines LEFT JOIN competitions ON competitions.id LIKE disciplines.compId WHERE disciplines.id LIKE ?");
  $stmt->execute(array($disId));    
}
else
{
 
$stmt = $db->prepare("SELECT disciplines.* FROM disciplines LEFT JOIN competitions ON competitions.id LIKE disciplines.compId WHERE disciplines.id LIKE ?");
      $stmt->execute(array($disId));
}
      $disRow = $stmt->fetch();
    if($disRow['id'] != '')
    {
        //permitted to show
          ?>
          <title class="title"><?php echo $disRow['title'] ?></title>
          <div class="container">
  <div class="row">


     <div onclick="createPanel('modules/start.php?id=7&disId=<?php echo $disRow['id']; ?>')" class="btn btn-primary col-sm m-1">
         <i class="fas fa-list fa-lg"></i><br>
        <input class="btn btn-primary" type="submit" value="View Start List"></input>
      </div>
      <div onclick="createPanel('modules/start.php?id=8&disId=<?php echo $disRow['id']; ?>')" class="btn btn-primary col-sm m-1">
         <i class="fas fa-trophy fa-lg"></i><br>
        <input class="btn btn-primary" type="submit" value="View Scoreboard"></input>
      </div>
  
  </div>
          </div>
          <?php } ?>