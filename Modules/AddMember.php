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
if(isset($memberId))
{
$stmt = $db->prepare("SELECT * FROM user WHERE id LIKE ?");
      $stmt->execute(array($memberId));
      $memberRow = $stmt->fetch();
}
?>

<script>
function updateDisciplines(element)
{
  var thisPanel = element.closest('.floatingPanel');
var compSelectForm = thisPanel.getElementsByClassName('competitionSelection')[0];
var disSelectForm = thisPanel.getElementsByClassName('disciplineSelection')[0];
var optionsArray = getSelectedOptions(compSelectForm);
//$(disSelectForm).load('./modules/helpers/getSelectedDisciplines.php?disId=<?php echo isset($disId)?$disId:''; ?>&compId=<?php echo isset($compId)?$compId:''; ?>&memberId=<?php echo isset($memberId)?$memberId:''; ?>', {optionsArray}, function(result) {console.log(result);});
var loader = addLoader(thisPanel);
$.ajax({
                url: './modules/helpers/getSelectedDisciplines.php?disId=<?php echo isset($disId)?$disId:''; ?>&compId=<?php echo isset($compId)?$compId:''; ?>&memberId=<?php echo isset($memberId)?$memberId:''; ?>',
                type: 'post',
                data: {"selectedComps":$(compSelectForm).val()},
                success: function(response){                   
                    disSelectForm.innerHTML = response;
                    $(loader).remove();
                }
            });
}

function presetPermissions(element)
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
updatePermissions(element);
}

function updatePermissions(element)
{
  var thisPanel = element.closest('.floatingPanel');
var judgeCriteriaContainer = thisPanel.getElementsByClassName('judgeCriteria')[0];
var disciplineSelectForm = thisPanel.getElementsByClassName('disciplineSelection')[0];
var permSelectForm = thisPanel.getElementsByClassName('permissionSelect')[0];

for(var i = 0; i<permSelectForm.options.length; i++)
{
  if(permSelectForm.options[i].value == "8" && permSelectForm.options[i].selected) //Can judge
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
      updateCriteria(element);
     }
  }
}
}

function updateCriteria(element)
{
  var thisPanel = element.closest('.floatingPanel');
var disciplineSelectForm = thisPanel.getElementsByClassName('disciplineSelection')[0];
var criteriaSelectForm = thisPanel.getElementsByClassName('criteriaSelection')[0];
var loader = addLoader(thisPanel);
$.ajax({
                url: './modules/helpers/getSelectedCriteria.php?disId=<?php echo isset($disId)?$disId:''; ?>&compId=<?php echo isset($compId)?$compId:''; ?>&memberId=<?php echo isset($memberId)?$memberId:''; ?>',
                type: 'post',
                data: {"selectedDisciplines":$(disciplineSelectForm).val()},
                success: function(response){                   
                  criteriaSelectForm.innerHTML = response;
                    $(loader).remove();
                }
            });
}

function submitForm(element)
{
  var thisPanel = element.closest('.floatingPanel');
var memberForm = thisPanel.getElementsByClassName('addMemberForm')[0];
var panelContent = thisPanel.getElementsByClassName('panelContent')[0];

  $.ajax({
    'url': './Modules/Helpers/addMemberToDatabase.php?disId=<?php echo isset($disId)?$disId:''; ?>&compId=<?php echo isset($compId)?$compId:''; ?>&memberId=<?php echo isset($memberId)?$memberId:''; ?>',
    'type': 'post',
    'data': $(memberForm).serializeArray(),
    'success': function(result){
         panelContent.innerHTML = result;
    }
});
}

function getSelectedOptions(element) {
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


<title class="title"><?php if(isset($memberId)) { echo "Update"; } else { echo "New"; } ?> Member</title>
<form class="addMemberForm" name="addMemberForm" onSubmit="submitForm(this)" action="javascript:void(0);">
  <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="validationDefault01">Formal Name*:</label>
      <input type="text" name="formalName" class="form-control" id="validationDefault01" value="<?php echo isset($memberId)?$memberRow['formalName']:'Enter Name'; ?>" required>
    </div>
    </div>
    <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="validationDefault01">Identifier*:</label>
      <input type="text" name="identifier" class="form-control" id="validationDefault01" value="<?php echo isset($memberId)?$memberRow['identifier']:"Enter Email"; ?>" required>
    </div>
    </div>
    <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="validationDefault01">Password*:</label>
      <input type="password" name="password" class="form-control" id="validationDefault01" value="<?php echo isset($memberId)?"Enter new password":"Enter password"; ?>" required>
    </div>
    </div>
    <div class="form-group">
    <label for="typeSelect">Permission presets</label>
    <select onLoad="presetPermissions(this)" onChange="presetPermissions(this)" name="" class="form-control permissionPresetSelect" id="typeSelect" required>
    <?php
    $stmt = $db->prepare("SELECT * FROM `permissionPresets` ORDER BY title");
    $stmt->execute();
    while($presetRow = $stmt->fetch(PDO::FETCH_ASSOC)) //Get all Permissions
    {
      $presetIsSelected = "";
      if(isset($memberId)) //If we're updating an existing Member
      { 
        if($memberRow['permissions'] == $presetRow['value']) //checking if member has the permission next in loop
      {
         $presetIsSelected = "SELECTED";
         $selectedComps[] = $compRow['id'];
      }
      }
      ?>
       <option value="<?php echo $presetRow['value']; ?>" <?php echo $presetIsSelected; ?>><?php echo $presetRow['title']; ?></option>
      <?php
    }
    ?>
      <option value="" <?php echo $presetIsSelected==""?"SELECTED":""; ?>>Custom Permissions</option>
    </select>
    <label for="typeSelect">Permissions</label>
    <select onChange="updatePermissions(this)" multiple name="permissions" class="form-control permissionSelect" id="typeSelect" required>
    <?php
    $stmt = $db->prepare("SELECT * FROM `permissions` ORDER BY title");
    $stmt->execute();
    while($permRow = $stmt->fetch(PDO::FETCH_ASSOC)) //Get all Permissions
    {
      $permIsSelected = "";
      if(isset($memberId)) //If we're updating an existing Member
      { 
        if(strpos($memberRow['permissions'], ','.$permRow['id'].',') != false) //checking if member has the permission next in loop
      {
         $permIsSelected = "SELECTED";
         $selectedComps[] = $compRow['id'];
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
    <select onChange="updateDisciplines(this)" multiple name="competitions" class="form-control competitionSelection" id="compSelect">
    <?php
    $stmt = $db->prepare("SELECT * FROM competitions WHERE environmentId LIKE ?");
    $stmt->execute(array($_SESSION['environmentId']));
    $selectedComps = array();
    while($compRow = $stmt->fetch(PDO::FETCH_ASSOC)) //Get all Competitions associated with the current Envionment Id
    {
      $compIsSelected = "";
      if(isset($memberId)) //If we're updating an existing Member
      { 
        if(strpos($compRow['associatedMembers'], ','.$memberRow['id'].',') != false) //checking if member is associated with next competition in loop
      {
         $compIsSelected = "SELECTED";
         $selectedComps[] = $compRow['id'];
      }
      }
      if(isset($compId)) //If we're adding a member to a specific competition
      { 
        if($compRow['id'] == $compId) //checking given competition is next competition in loop
      {
         $compIsSelected = "SELECTED";
         $selectedComps[] = $compRow['id'];
      }
      }
      if(isset($disId)) //If we're adding a member to a specific discipline
      { 
        $stmt = $db->prepare("SELECT * FROM disciplines WHERE id LIKE ?");
      $stmt->execute(array($disId));
   $tempDisRow = $stmt->fetch(); 
    
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
    <select onChange="updatePermissions(this)"  multiple name="disciplines" class="form-control disciplineSelection" id="disSelect">
    <?php include("./helpers/getSelectedDisciplines.php"); ?>
    </select>
  </div>
  <div id="judgeCriteria" class="form-group judgeCriteria" style="display:<?php if (isset($memberId)){ echo strpos($memberRow['permissions'], ',8,')!=false?'block':'none';}else{echo'none';} ?>">
    <label for="criteriaSelect">Criteria to Judge</label>
    <select multiple name="criteria" class="form-control criteriaSelection" id="criteriaSelect">
    <?php include("./helpers/getSelectedCriteria.php"); ?>
    </select>
  </div>

  <button class="btn btn-primary" type="submit">Add</button>
</form>