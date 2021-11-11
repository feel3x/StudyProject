<?php include("../includeSafety.php"); ?>
<div class="container">
  <div class="row">
          <?php

$userId = $_SESSION['userId'];
$userIdInCommas = ",".$userId.",";
$searchUserId ="%$userIdInCommas%";
$stmt = $db->prepare("SELECT * FROM competitions WHERE associatedMembers LIKE ? ORDER BY `date` DESC");
      $stmt->execute(array($searchUserId));
      while($compRow = $stmt->fetch(PDO::FETCH_ASSOC))
      {
?>
       <div onclick="goTo(this, 'modules/start.php?id=4&compId=<?php echo $compRow['id']; ?>')" class="btn btn-primary col-sm m-1">
         <i class="fas fa-chess fa-lg"></i><br>
        <input class="btn btn-primary" type="submit" value="<?php echo $compRow['title']; ?>"></input>
      </div>
<?php
      }

      ?>
      </div>
</div>