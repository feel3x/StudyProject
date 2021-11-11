
<?php include("../includeSafety.php"); ?>
<?php 

if(isset( $_GET['compId']))
{
    $compId = $_GET['compId'];
} 
$userId = $_SESSION['userId'];
$userIdInCommas = ",".$userId.",";
$searchUserId ="%$userIdInCommas%";
$stmt = $db->prepare("SELECT * FROM competitions WHERE id LIKE ? AND associatedMembers LIKE ?");
      $stmt->execute(array($compId, $searchUserId));
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