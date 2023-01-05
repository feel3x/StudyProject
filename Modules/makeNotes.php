<?php

/**
* This Module gives the user the ability to make personal notes.
* The notes will be saved in the database and are only accessibly by this user. 
* The user can make seperate notes for each module they find themselves in. Such as different competitions or disciplines will have separate notes.
*/

include("../includeSafety.php");
if(!checkPermission("Logged In") || !isset($_SESSION['userId']) || $_SESSION['userId'] == '')
{
    echo "no permissions";
    exit;
}

$noteLocation = explode("panelId", $_SERVER['REQUEST_URI'])[0];
//query note
$stmt = $db->prepare("SELECT * FROM `notes` WHERE noteLocation LIKE ? AND userId LIKE ?");
   $stmt->execute(array($noteLocation, $_SESSION['userId']));
 $noteRow = $stmt->fetch();
 if($noteRow)
 {
 $noteValue = $noteRow['noteValue'];
 }
 else
 {
     $noteValue = "";
 }

if(isset($_POST['noteValue']))
{
   
    //update or insert note
    $noteValue = $_POST['noteValue'];
    if($noteValue != null || $noteValue != '')
    {
        if($noteRow == false || $noteRow['id'] == '')
        {
            //new Note
            $eintrag = $db->prepare("INSERT INTO notes (userId, noteLocation, noteValue) VALUES (?, ?, ?)");
            $eintrag->execute(array($_SESSION['userId'], $noteLocation, $noteValue)) ;   
        }
        else
        {
            //update note
            $eintrag = $db->prepare("UPDATE notes SET noteValue = ? WHERE id LIKE ?");
            $eintrag->execute(array($noteValue, $noteRow['id'])) ;   
        }  
  
    }
}

$panelId = isset($_GET['panelId'])?$_GET['panelId']:null;
?>
<script>
    function MAKENOTESsubmitScore(element)
{
    noteValue = element.value;
    var postArray = {'noteValue':noteValue};
    refreshPanel('<?php echo $panelId;?>', postArray, true);
}
</script>
<textarea  class="form-control"  onChange="MAKENOTESsubmitScore(this)" style="width:100%; height: 100%; opacity: 0.8">
<?php echo $noteValue ?>
</textarea>