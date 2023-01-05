
<?php 
/**
 * This module displays information about the competition a list of buttons for each discipline contained by the competition.
 * Each button represents a discipline and allows users to view information about the discipline. The list of disciplines is retrieved from the database.
 * The competition that the disciplines belong to is specified by the `compId` URL parameter.
 */

include("../includeSafety.php"); 



if(isset( $_GET['compId']))
{
    $compId = $_GET['compId'];
} 

if(in_array(11, $_SESSION['userPermissions']))
{
  $stmt = $db->prepare("SELECT * FROM competitions WHERE id LIKE ? ");
  $stmt->execute(array($compId)); 
}
else
{
$stmt = $db->prepare("SELECT * FROM competitions WHERE id LIKE ?");
      $stmt->execute(array($compId));
}
      $compRow = $stmt->fetch()
    
          ?>
          <title class="title"><?php echo $compRow['title'] ?></title>
          <div class="container">
  <div class="row">

    <?php      $stmt = $db->prepare("SELECT * FROM disciplines WHERE compId LIKE ?");
      $stmt->execute(array($compId));
      while($disRow = $stmt->fetch(PDO::FETCH_ASSOC))
      {
?>
     <div onclick="goTo(this, 'modules/start.php?id=6&disId=<?php echo $disRow['id']; ?>')" class="btn btn-primary col-sm m-1">
         <i class="fas fa-chess-board fa-lg"></i><br>
        <input class="btn btn-primary" type="submit" value="<?php echo $disRow['title']; ?>"></input>
      </div>
<?php
      }    
      ?>   
  </div>
          </div>