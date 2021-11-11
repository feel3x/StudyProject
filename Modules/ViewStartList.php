
<?php
if(isset($_GET['disId']))
{
    $disId = $_GET['disId'];
}

$userId = $_SESSION['userId'];
$userIdInCommas = ",".$userId.",";
$searchUserId ="%$userIdInCommas%";
$stmt = $db->prepare("SELECT disciplines.* FROM disciplines LEFT JOIN competitions ON competitions.id LIKE disciplines.compId WHERE disciplines.id LIKE ? AND competitions.associatedMembers LIKE ? ORDER BY `date` DESC");
      $stmt->execute(array($disId, $searchUserId));
      $disRow = $stmt->fetch();
      if($disRow['id'] != '')
      {
        //Permitted to show
        ?>
<title class="title"><?php echo $disRow['title']; ?> - Startlist</title>

<script>
$(document).ready(function(){
  setTimeout(function() { doRefresh(); }, 5000);
function doRefresh()
{
  var panelElement = document.getElementById('thisPage');
  if(panelElement != null)
  {
  refreshPanel(panelElement.closest('.floatingPanel').id);
  }
}
});

</script>
<div class="container">
  <div class="row w-100">
    <div class="col-sm"><a href="#" Onclick="printContent(this)"><i class="fas fa-print"></i></a></div>
     <div class="col-sm"><input type="checkbox" id="scrollWithComp" value="" class="scrollWithCompetitor" CHECKED>
     <label class="form-check-label" for="scrollWithComp">
  Autoscroll
  </label><div>

  </div>
</div>
<div id="thisPage" class="container sectionToPrint">          
  <table stlye="font-size: inherit;" id="testTable" class="table table-striped">
    <thead style="background-color: black; color: white; position: sticky;top: 0">
      <tr style="position: sticky;top: 0">
        <th style="position: sticky;top: 0">#</th>
        <th style="position: sticky;top: 0">Name</th>
      </tr style="position: sticky;top: 0">
    </thead>
    <tbody>

<?php


      

      $competitors = str_replace(',', '', explode(',,', $disRow['associatedMembers']));
      $in  = str_repeat('?,', count($competitors) - 1) . '?';
      $orderBy = $disRow['startListSort'];
$stmt = $db->prepare("SELECT * FROM user WHERE id in($in) AND `permissions` LIKE '%,6,%' ORDER BY $orderBy");
      $stmt->execute($competitors);
      $startNumber = 0;
      while($startRow = $stmt->fetch(PDO::FETCH_ASSOC))
      {
          $startNumber++;
?>
       <tr>
        <td><?php echo $startNumber; ?></td>
        <td><?php echo $startRow['formalName']; ?></td>
      </tr>
<?php
      }
    
      ?>

</tbody>
  </table>
</div>
<?php
}
else

{ echo "Something went wrong";}
?>