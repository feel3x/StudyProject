<?php include("../includeSafety.php"); ?>

<?php 
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
$addMemberRootPage = true;

//If there is a member Id, query the data from database
if(isset($memberId) && $memberId != "")
{
$stmt = $db->prepare("SELECT * FROM user WHERE id LIKE ?");
      $stmt->execute(array($memberId));
      $memberRow = $stmt->fetch();
}
?>

<script>
function ADDMEMBERupdateDisciplines(element)
{
  var thisPanel = element.closest('.floatingPanel');
var compSelectForm = thisPanel.getElementsByClassName('competitionSelection')[0];
var disSelectForm = thisPanel.getElementsByClassName('disciplineSelection')[0];
var optionsArray = getSelectedOptions(compSelectForm);
var loader = addLoader(thisPanel);
$.ajax({
                url: './modules/helpers/getSelectedDisciplines.php?disId=<?php echo isset($disId)?$disId:''; ?>&compId=<?php echo isset($compId)?$compId:''; ?>&memberId=<?php echo isset($memberId)?$memberId:''; ?>',
                type: 'post',
                data: {"selectedComps":$(compSelectForm).val()},
                success: function(response){                   
                    disSelectForm.innerHTML = response;
                    $(loader).remove();
                    ADDMEMBERupdatePermissions(element);
                }
            });
}

function ADDMEMBERpresetPermissions(element)
{
  var thisPanel = element.closest('.floatingPanel');
var permPresetSelectForm = thisPanel.getElementsByClassName('permissionPresetSelect')[0];
var permSelectForm = thisPanel.getElementsByClassName('permissionSelect')[0];

var selectedPreset = permPresetSelectForm.value;
permissionIds = selectedPreset.split(',,');
for(var i = 0; i<permissionIds.length; i++)
{
  permissionIds[i] = permissionIds[i].replace(',', '');
}
for (var i = 0; i < permSelectForm.options.length; i++) {
  if(permissionIds.includes(permSelectForm.options[i].value))
  { 
    permSelectForm.options[i].selected = true; 
  }
  else
  {
    permSelectForm.options[i].selected = false; 
  }
}
ADDMEMBERupdatePermissions(element);
}

function ADDMEMBERupdatePermissions(element)
{
  var thisPanel = element.closest('.floatingPanel');
var judgeCriteriaContainer = thisPanel.getElementsByClassName('judgeCriteria')[0];
var disciplineSelectForm = thisPanel.getElementsByClassName('disciplineSelection')[0];
var permSelectForm = thisPanel.getElementsByClassName('permissionSelect')[0];
judgeCriteriaContainer.style.display = 'none';
for(var i = 0; i<permSelectForm.options.length; i++)
{
  if(permSelectForm.options[i].value == "7" && permSelectForm.options[i].selected) //Can judge
  {
    var hasSelection = false;
    for (var j = 0; j < disciplineSelectForm.options.length; j += 1) {
      if (disciplineSelectForm.options[j].selected) {
         hasSelection = true;
      }
    }
     if(hasSelection)
     {
      judgeCriteriaContainer.style.display = 'block';
      break;
     }
}
}
ADDMEMBERupdateCriteria(element);
}

function ADDMEMBERupdateCriteria(element)
{
  var thisPanel = element.closest('.floatingPanel');
var disciplineSelectForm = thisPanel.getElementsByClassName('disciplineSelection')[0];
var criteriaSelectForm = thisPanel.getElementsByClassName('criteriaSelection')[0];
var loader = addLoader(thisPanel);
$.ajax({
                url: './modules/helpers/getSelectedCriteria.php?disId=<?php echo isset($disId)?$disId:''; ?>&compId=<?php echo isset($compId)?$compId:''; ?>&memberId=<?php echo (isset($memberId) && $memberId != "")?$memberId:''; ?>',
                type: 'post',
                data: {"selectedDisciplines":$(disciplineSelectForm).val()},
                success: function(response){                   
                  criteriaSelectForm.innerHTML = response;
                    $(loader).remove();
                }
              
            });
}



function ADDMEMBERsubmitForm(element)
{
  var thisPanel = element.closest('.floatingPanel');
var memberForm = thisPanel.getElementsByClassName('addMemberForm')[0];
var panelContent = thisPanel.getElementsByClassName('panelContent')[0];
var loader = addLoader(thisPanel);
  $.ajax({
    'url': './modules/helpers/addMemberToDatabase.php?disId=<?php echo (isset($disId) && $disId != "")?$disId:''; ?>&compId=<?php echo (isset($compId) && $compId != "")?$compId:''; ?>&memberId=<?php echo (isset($memberId) && $memberId != "")?$memberId:''; ?>',
    'type': 'post',
    'data': $(memberForm).serializeArray(),
    success: function(result){
      console.log(result);
        if(result == "success")
        {
        <?php  if(isset($memberId) && $memberId != "")
        {
        ?>
        panelContent.innerHTML = "Member updated! <br> <button OnClick='closePanel(\"" + thisPanel.id + "\")' class='btn btn-primary'>close window</button>";

      <?php  }
        else
        {
          ?>
          panelContent.innerHTML = "Member added! <br> <button OnClick='refreshPanel(\"" + thisPanel.id + "\")' class='btn btn-primary'>Add another member</button>";
        <?php } ?>
    
    }
    else
    {
      popMessage('Error', result, function(){}, true);
    }
     $(loader).remove();
  }
});
}

function ADDMEMBERgetSelectedOptions(element) {
    // validate element
    if(!element || !element.options)
        return []; 

    // return HTML5 implementation of selectedOptions.
    if (element.selectedOptions)
        return element.selectedOptions;

    // do this if browser doesn't have the HTML5 selectedOptions
    var opts = element.options;
    var selectedOptions = [];
    for(var i = 0; i < opts.length; i++) {
         if(opts[i].selected) {
             selectedOptions.push(opts[i]);
         }
    }
    return selectedOptions;
}
</script>


<title class="title"><?php if(isset($memberId) && $memberId != "") { echo "Update"; } else { echo "New"; } ?> Member</title>
<form class="addMemberForm" name="addMemberForm" onSubmit="ADDMEMBERsubmitForm(this)" action="javascript:void(0);">
  <div class="form-row">
  <div class="col-md-4 mb-3">
      <label for="validationDefault01">First Name*:</label>
      <input type="text" name="firstName" class="form-control" id="validationDefault01" value="<?php echo isset($memberId)?$memberRow['firstName']:'Enter first Name'; ?>"  <?php echo checkPermission("Update Critical Member Info")? "":"DISABLED"; ?>>
    </div>
    <div class="col-md-4 mb-3">
      <label for="validationDefault01">Last Name*:</label>
      <input type="text" name="lastName" class="form-control" id="validationDefault01" value="<?php echo isset($memberId)?$memberRow['lastName']:'Enter last Name'; ?>" required  <?php echo checkPermission("Update Critical Member Info")? "":"DISABLED"; ?>>
    </div>
    </div>
    <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="validationDefault01">Identifier*:</label>
      <input type="text" name="identifier" class="form-control" id="validationDefault01" value="<?php echo isset($memberId)?$memberRow['identifier']:"Enter Email"; ?>" required  <?php echo checkPermission("Update Critical Member Info")? "":"DISABLED"; ?>>
    </div>
    </div>
    <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="validationDefault01">Password*:</label>
      <input type="password" name="password" class="form-control" id="validationDefault01" value="" <?php if(!isset($memberId)|| $memberId == ""){ echo 'required'; }?> <?php echo checkPermission("Update Critical Member Info")? "":"DISABLED"; ?>>
    </div>
    </div>
    <div class="form-group">
    <label for="typeSelect">Permission presets</label>
    <select onChange="ADDMEMBERpresetPermissions(this)" name="" class="form-control permissionPresetSelect" id="typeSelect" required  <?php echo checkPermission("Update Critical Member Info")? "":"DISABLED"; ?>>
    <?php
      if(isset($memberId) && $memberId != "") //If we're updating an existing Member
      {
            $memberPermissions = explode(",,",$memberRow['permissions']);
            $memberPermissions = str_replace(",","", $memberPermissions);
      }
      $presetWasSelected = false;
            
    $stmt = $db->prepare("SELECT * FROM `permissionpresets` ORDER BY title");
    $stmt->execute();
    while($presetRow = $stmt->fetch(PDO::FETCH_ASSOC)) //Get all Permissions presets
    {
      $presetIsSelected = "";
      if(isset($memberId) && $memberId != "") //If we're updating an existing Member
      { 
        $presetPermissions = explode(",,", $presetRow['value']);
        $presetPermissions = str_replace(",","", $presetPermissions);
        if(count($memberPermissions) == count($presetPermissions)) //checking if member has the permission next in loop
      {
        echo "hier";
         $presetIsSelected = "SELECTED";
         for($i = 0; $i<count($presetPermissions); $i++)
         {
           if(!in_array($presetPermissions[$i], $memberPermissions))
           {
            $presetIsSelected = "";
             break;
           }
         }
        // $selectedComps[] = $compRow['id'];
      }
      }
      if($presetIsSelected != "")
      {
        $presetWasSelected = true;
      }
      ?>
       <option value="<?php echo $presetRow['value']; ?>" <?php echo $presetIsSelected; ?>><?php echo $presetRow['title']; ?></option>
      <?php
    }
    ?>
      <option value="" <?php echo ($presetWasSelected?"":"SELECTED"); ?>>Custom Permissions</option>
    </select>
    <label for="typeSelect">Permissions</label>
    <select onChange="ADDMEMBERupdatePermissions(this)" multiple name="permissions[]" class="form-control permissionSelect" id="typeSelect" required  <?php echo checkPermission("Update Critical Member Info")? "":"DISABLED"; ?>>
    <?php
    $stmt = $db->prepare("SELECT * FROM `permissions` ORDER BY title");
    $stmt->execute();
    while($permRow = $stmt->fetch(PDO::FETCH_ASSOC)) //Get all Permissions
    {
      $permIsSelected = "";
      if(isset($memberId) && $memberId != "") //If we're updating an existing Member
      { 
        if(in_array($permRow['id'], $memberPermissions)) //checking if member has the permission next in loop
      {
         $permIsSelected = "SELECTED";
       //  $selectedComps[] = $compRow['id'];
      }
      }
      ?>
       <option value="<?php echo $permRow['id']; ?>" <?php echo $permIsSelected; ?>><?php echo $permRow['title']; ?></option>
      <?php
    }
    ?>
    </select>
  </div>
  <div class="form-group">
    <label for="compSelect">Competitions</label>
    <select onChange="ADDMEMBERupdateDisciplines(this)" multiple name="competitions[]" class="form-control competitionSelection" id="compSelect">
    <?php
    $stmt = $db->prepare("SELECT * FROM competitions WHERE environmentId LIKE ? AND hide LIKE ?");
    $stmt->execute(array($_SESSION['environmentId'], 0));
    $selectedComps = array();
    while($compRow = $stmt->fetch(PDO::FETCH_ASSOC)) //Get all Competitions associated with the current Envionment Id
    {
      $compIsSelected = "";
      if(isset($memberId) && $memberId != "") //If we're updating an existing Member
      { 
        if(strpos($compRow['associatedMembers'], ','.$memberRow['id'].',') != false) //checking if member is associated with next competition in loop
      {
         $compIsSelected = "SELECTED";
         $selectedComps[] = $compRow['id'];
      }
      }
      if(isset($compId) && $compId != "") //If we're adding a member to a specific competition
      { 
        if($compRow['id'] == $compId) //checking given competition is next competition in loop
      {
         $compIsSelected = "SELECTED";
         $selectedComps[] = $compRow['id'];
      }
      }
      if(isset($disId) && $disId != "") //If we're adding a member to a specific discipline
      { 
        $stmt2 = $db->prepare("SELECT * FROM disciplines WHERE id LIKE ?");
      $stmt2->execute(array($disId));
   $tempDisRow = $stmt2->fetch(); 
    
        if($tempDisRow['compId'] == $compRow['id']) //checking given competition is next competition in loop
      {
         $compIsSelected = "SELECTED";
         $selectedComps[] = $compRow['id'];
      }
      }
      ?>
       <option value="<?php echo $compRow['id']; ?>" <?php echo $compIsSelected; ?>><?php echo $compRow['title']; ?></option>
      <?php
    }
    ?>
    </select>
  </div>
  <div class="form-group">
    <label for="disSelect">Diciplines</label>
    <select onChange="ADDMEMBERupdatePermissions(this)"  multiple name="disciplines[]" class="form-control disciplineSelection" id="disSelect">
    <?php include("./helpers/getSelectedDisciplines.php"); ?>
    </select>
  </div>
  <div id="judgeCriteria" class="form-group judgeCriteria" style="display:<?php if (isset($memberId) && $memberId != ""){ echo strpos($memberRow['permissions'], ',7,')!=false?'block':'none';}else{echo'none';} ?>">
    <label for="criteriaSelect">Criteria to Judge</label>
    <select multiple name="criteria[]" class="form-control criteriaSelection" id="criteriaSelect">
    <?php include("./helpers/getSelectedCriteria.php"); ?>
    </select>
  </div>
 
  <div class="form-group">
  <button class="btn btn-primary" type="submit"><?php echo (isset($memberId) && $memberId != "")?"Update":"Add"; ?></button>
  </div>
</form>
