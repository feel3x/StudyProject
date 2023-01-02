<?php

include_once("../../connect.php");

if(!checkPermission("Delete Members"))
{
echo "No Permissions!";
exit;
}
$selectedMembers = $_POST['memberIdSelected'];
if(isset($_POST['go']) && $_POST['go'] == "1")
{
    //process deleting members
    $deleteString = "(";
    for($i = 0; $i< count($selectedMembers); $i++)
    {
        if($selectedMembers[$i] == $_SESSION['userId'])
        {
           echo "Don't delete yourself!";
            exit;
        }
        if($i != 0)
        {
        $deleteString = $deleteString.",";
        }
        $deleteString = $deleteString."?";
    }
    $deleteString = $deleteString.")";
    $stmt = $db->prepare("DELETE FROM user WHERE id IN $deleteString");
    $stmt->execute($selectedMembers);
    $stmt = $db->prepare("DELETE FROM judging WHERE userId IN $deleteString");
    $stmt->execute($selectedMembers);
    echo "success";
    exit;
}
?>
<script>
function DELETEMEMBERsubmitForm(element)
{
  var thisPanel = element.closest('.floatingPanel');
var memberForm = thisPanel.getElementsByClassName('deleteMemberForm')[0];
var panelContent = thisPanel.getElementsByClassName('panelContent')[0];
var loader = addLoader(thisPanel);
  $.ajax({
    'url': './modules/helpers/deleteMembers.php',
    'type': 'post',
    'data': $(memberForm).serializeArray(),
    success: function(result){
      console.log(result);
        if(result == "success")
        {
       
        panelContent.innerHTML = "Members deleted! <br> <button OnClick='closePanel(\"" + thisPanel.id + "\")' class='btn btn-primary'>close window</button>";

     
    
    }
    else
    {
      popMessage('Error', result, function(){}, true);
    }
     $(loader).remove();
  }
});
}

</script>
<form class="deleteMemberForm" name="deleteMemberForm" onSubmit="DELETEMEMBERsubmitForm(this)" action="javascript:void(0);">
<input type="hidden" name="go" value="1">
    <?php
foreach($selectedMembers as $value)
{
    echo '<input type="hidden" name="memberIdSelected[]" value="'. $value. '">';
}
    ?>
    Are you sure you want to <b>Delete</b>
  <div class="form-group">
  <button class="btn btn-danger" type="submit">Delete <?php echo count($selectedMembers); ?> Members</button>
  </div>

</form>