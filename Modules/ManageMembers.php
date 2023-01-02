<?php include("../includeSafety.php"); ?>

<?php 
if(isset($_GET['disId']))
{
    $disId = $_GET['disId'];
}
if(isset($_GET['compId']))
{
    $compId = $_GET['compId'];
}
$addMemberRootPage = true;
?>

<script>




function updateDisciplines(element)
{
  var thisPanel = element.closest('.floatingPanel');
var compSelectForm = thisPanel.getElementsByClassName('competitionSelection')[0];
var disSelectForm = thisPanel.getElementsByClassName('disciplineSelection')[0];
var optionsArray = getSelectedOptions(compSelectForm);
var loader = addLoader(thisPanel);
$.ajax({
                url: './modules/helpers/getSelectedDisciplines.php?disId=<?php echo (isset($disId) && $disId != "")?$disId:''; ?>&compId=<?php echo (isset($compId) && $compId != "")?$compId:''; ?>',
                type: 'post',
                data: {"selectedComps":$(compSelectForm).val()},
                success: function(response){                   
                    disSelectForm.innerHTML = response;
                    $(loader).remove();
                    updatePermissions(element);
                }
            });
}

function showHideFilters(element)
{
    var thisPanel = element.closest('.floatingPanel');
var filterDiv = thisPanel.getElementsByClassName('filterDiv')[0];

$(filterDiv).animate({
      width: 'toggle',
      height: 'toggle'
    }, "slow");
}

function presetPermissions(element)
{
  var thisPanel = element.closest('.floatingPanel');
var permPresetSelectForm = thisPanel.getElementsByClassName('permissionPresetSelect')[0];
var permSelectForm = thisPanel.getElementsByClassName('permissionSelect')[0];
var customPresets = thisPanel.getElementsByClassName('customPresets')[0];

var selectedPreset = permPresetSelectForm.value;
if(selectedPreset == '')
{
customPresets.style.display ="block";
}
else
{
customPresets.style.display = "none";
}
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
var scoreCriteriaContainer = thisPanel.getElementsByClassName('scoreCriteria')[0];
var disciplineSelectForm = thisPanel.getElementsByClassName('disciplineSelection')[0];
var permSelectForm = thisPanel.getElementsByClassName('permissionSelect')[0];
judgeCriteriaContainer.style.display = 'none';
scoreCriteriaContainer.style.display = 'none';
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
if(permSelectForm.options[i].value == "9"  && permSelectForm.options[i].selected) //Can compete
{
    var amountSelected = 0;
    for (var j = 0; j < disciplineSelectForm.options.length; j += 1) {
      if (disciplineSelectForm.options[j].selected) {
         amountSelected++;
      }
    } 
    if(amountSelected == 1)
    {
      
      scoreCriteriaContainer.style.display = 'block';
    }  
}
}
updateCriteria(element);
updateScoreCriteria(element);
}

function updateCriteria(element)
{
  var thisPanel = element.closest('.floatingPanel');
var disciplineSelectForm = thisPanel.getElementsByClassName('disciplineSelection')[0];
var criteriaSelectForm = thisPanel.getElementsByClassName('criteriaSelection')[0];
var loader = addLoader(thisPanel);
$.ajax({
                url: './modules/helpers/getSelectedCriteria.php?disId=<?php echo (isset($disId) && $disId != "")?$disId:''; ?>&compId=<?php echo (isset($compId) && $compId != "")?$compId:''; ?>',
                type: 'post',
                data: {"selectedDisciplines":$(disciplineSelectForm).val()},
                success: function(response){                   
                  criteriaSelectForm.innerHTML = response;
                    $(loader).remove();
                }
              
            });

            MANAGEMEMEBERSsubmitForm(element);
}

function updateScoreCriteria(element)
{
  var thisPanel = element.closest('.floatingPanel');
var disciplineSelectForm = thisPanel.getElementsByClassName('disciplineSelection')[0];
var criteriaSelectForm = thisPanel.getElementsByClassName('scoreSelection')[0];
var loader = addLoader(thisPanel);
$.ajax({
                url: './modules/helpers/getSelectedCriteria.php?disId=<?php echo (isset($disId) && $disId != "")?$disId:''; ?>&compId=<?php echo (isset($compId) && $compId != "")?$compId:''; ?>',
                type: 'post',
                data: {"selectedDisciplines":$(disciplineSelectForm).val()},
                success: function(response){                   
                  criteriaSelectForm.innerHTML = response+'<option value="score" SELECTED>Score</option>';
                    $(loader).remove();
                }
              
            });
}


function MANAGEMEMEBERSsubmitForm(element)
{
  var thisPanel = element.closest('.floatingPanel');
var memberForm = thisPanel.getElementsByClassName('addMemberForm')[0];
var mainContent = thisPanel.getElementsByClassName('mainContent')[0];
var loader = addLoader(thisPanel);
  $.ajax({
    'url': './modules/helpers/listMembers.php?disId=<?php echo (isset($disId) && $disId != "")?$disId:''; ?>&compId=<?php echo (isset($compId) && $compId != "")?$compId:''; ?>',
    'type': 'post',
    'data': $(memberForm).serializeArray(),
    success: function(result){
        console.log(result);
      mainContent.innerHTML = result;

     $(loader).remove();
  }
});
}

function MANAGEMEMBERSdeleteMemberIds(element)
{
  var thisPanel = getPanel(element);
  var memberIdForm = thisPanel.getElementsByClassName('memberIdForm')[0];
  console.log($(memberIdForm).serializeArray());
goTo(element, './modules/helpers/deleteMembers.php?disId=<?php echo (isset($disId) && $disId != "")?$disId:''; ?>&compId=<?php echo (isset($compId) && $compId != "")?$compId:''; ?>',
$(memberIdForm).serializeArray());
}

function MANAGEMEMBERSmoveMemberIds(element)
{
  var thisPanel = getPanel(element);
  var memberIdForm = thisPanel.getElementsByClassName('memberIdForm')[0];
  console.log($(memberIdForm).serializeArray());
goTo(element, './modules/helpers/moveMembers.php?disId=<?php echo (isset($disId) && $disId != "")?$disId:''; ?>&compId=<?php echo (isset($compId) && $compId != "")?$compId:''; ?>',
$(memberIdForm).serializeArray());
}


var currentSorting = 0;
function sortTable(table, n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  currentSorting = n;
  table = table.closest("table");
  switching = true;
  // Set the sorting direction to ascending:
  dir = "asc";
  /* Make a loop that will continue until
  no switching has been done: */
  while (switching) {
    // Start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /* Loop through all table rows (except the
    first, which contains table headers): */
    for (i = 1; i < (rows.length - 1); i++) {
      // Start by saying there should be no switching:
      shouldSwitch = false;
      /* Get the two elements you want to compare,
      one from current row and one from the next: */
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /* Check if the two rows should switch place,
      based on the direction, asc or desc: */
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /* If a switch has been marked, make the switch
      and mark that a switch has been done: */
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      // Each time a switch is done, increase this count by 1:
      switchcount ++;
    } else {
      /* If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again. */
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
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
function MANAGEMEMEBERScheckCheckedBoxes(element)
{
  var panel = getPanel(element);
  var checkboxes = panel.getElementsByClassName('memberCheckbox');
  var foundChecked = false;
  for(var i = 0; i<checkboxes.length; i++)
  {
    if(checkboxes[i].checked)
    {
      $(panel.getElementsByClassName('memberActionDiv')[0]).slideDown(200);
      foundChecked = true;
      break;
    }
  }
  if(!foundChecked)
  {
    $(panel.getElementsByClassName('memberActionDiv')[0]).slideUp(200);
  }
}

</script>


<title class="title">Manage Members</title>
<div class="container">
  <div class="row">
      <div class="col-sm-auto m-1">
  <div onClick="showHideFilters(this)" class="col-sm-auto"><button class="btn-dark rounded-top"><i class="fa fa-filter"></i>    </button></div>
<div class="border border-dark rounded-right p-2 filterDiv">
    <h5>Filters:</h5>
<form class="addMemberForm" name="addMemberForm" onSubmit="MANAGEMEMEBERSsubmitForm(this)" action="javascript:void(0);">
    <div class="form-group">
    <label for="typeSelect">Roles</label>
    <select onLoad="presetPermissions(this)" onChange="presetPermissions(this)" name="presets" class="form-control permissionPresetSelect" id="typeSelect">
    <?php
    $stmt = $db->prepare("SELECT * FROM `permissionpresets` ORDER BY title");
    $stmt->execute();
    while($presetRow = $stmt->fetch(PDO::FETCH_ASSOC)) //Get all Permissions
    {
      ?>
       <option value="<?php echo $presetRow['value']; ?>" ><?php echo $presetRow['title']; ?></option>
      <?php
    }
    ?>
      <option value="custom" SELECTED>Custom Permissions</option>
    </select>
    <div style="display: <?php echo checkPermission("Update Critical Member Info")? "block":"none"; ?>;" class="customPresets">
    <div style="display: block;" class="customPresets">
    <label for="typeSelect">Permissions</label>
    <select onChange="updatePermissions(this)" multiple name="permissions[]" class="form-control permissionSelect" id="typeSelect">
    <?php
    $stmt = $db->prepare("SELECT * FROM `permissions` ORDER BY title");
    $stmt->execute();
    while($permRow = $stmt->fetch(PDO::FETCH_ASSOC)) //Get all Permissions
    {

      ?>
       <option value="<?php echo $permRow['id']; ?>"><?php echo $permRow['title']; ?></option>
      <?php
    }
    ?>
    </select>
  </div>
  </div>
</div>
  <div class="form-group">
    <label for="compSelect">Competitions</label>
    <select onChange="updateDisciplines(this)" multiple name="competitions[]" class="form-control competitionSelection" id="compSelect">
    <?php
    $stmt = $db->prepare("SELECT * FROM competitions WHERE environmentId LIKE ? AND hide LIKE ?");
    $stmt->execute(array($_SESSION['environmentId'], 0));
    $selectedComps = array();
    while($compRow = $stmt->fetch(PDO::FETCH_ASSOC)) //Get all Competitions associated with the current Envionment Id
    {
      $compIsSelected = "";
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
    <select onChange="updatePermissions(this)"  multiple name="disciplines[]" class="form-control disciplineSelection" id="disSelect">
    <?php include("./helpers/getSelectedDisciplines.php"); ?>
    </select>
  </div>
  <div  class="form-group judgeCriteria" style="display:none">
    <label for="criteriaSelect">Criteria to Judge</label>
    <select multiple onChange="MANAGEMEMEBERSsubmitForm(this)" name="criteria[]" class="form-control criteriaSelection" id="criteriaSelect">
    <?php include("./helpers/getSelectedCriteria.php"); ?>
    </select>
  </div>
  <div class="form-group scoreCriteria" style="display:none">
    <label for="scoreSelect">Show Scores</label>
    <select multiple onChange="MANAGEMEMEBERSsubmitForm(this)" name="scoreTypes[]" class="form-control scoreSelection" id="scoreSelect">
    <?php include("./helpers/getSelectedCriteria.php"); ?>
    </select>
  </div>
</form>
</div>
</div>

<div onLoad="initManageMembers()" class="mainContent col-lg m-1">main</div>
</div>
</div>
<iframe style="width:0; height:0;" onload="MANAGEMEMEBERSsubmitForm(this)"></iframe>