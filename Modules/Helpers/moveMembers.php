<?php

/**
 * This module helper script handles moving and adding members to other disciplines and compeitions
 */


include_once("../../connect.php");

if (!checkPermission("Move Members")) {
  echo "No Permissions!";
  exit;
}

$addMemberRootPage = true;
$selectedMembers = $_POST['memberIdSelected'];


if (isset($_POST['go']) && $_POST['go'] == "1") {
  //process copying members
  $addString = "";
  foreach ($selectedMembers as $memberId) {
    $addString = $addString . "," . $memberId . ",";
  }
  if (isset($_POST['competitions'])) {
    $compIds = $_POST['competitions'];
    //insert into the comps
    $compInString = "(";
    for ($i = 0; $i < count($compIds); $i++) {
      if ($i != 0) {
        $compInString = $compInString . ",";
      }
      $compInString = $compInString . "?";
    }
    $compInString = $compInString . ")";
    $eintrag = $db->prepare("UPDATE competitions SET associatedMembers = concat(associatedMembers, ?) WHERE id IN $compInString");
    $eintrag->execute(array_merge(array($addString), $compIds));
  }
  if (isset($_POST['disciplines'])) {
    $compIds = $_POST['disciplines'];
    //insert into the disciplines
    $compInString = "(";
    for ($i = 0; $i < count($compIds); $i++) {
      if ($i != 0) {
        $compInString = $compInString . ",";
      }
      $compInString = $compInString . "?";
    }
    $compInString = $compInString . ")";
    $eintrag = $db->prepare("UPDATE disciplines SET associatedMembers = concat(associatedMembers, ?) WHERE id IN $compInString");
    $eintrag->execute(array_merge(array($addString), $compIds));
  }
  echo "success";
  exit;
}
?>
<title class="title">Copy Members</title>
<script>
  function MOVEMEMBERupdateDisciplines(element) {
    var thisPanel = element.closest('.floatingPanel');
    var compSelectForm = thisPanel.getElementsByClassName('competitionSelection')[0];
    var disSelectForm = thisPanel.getElementsByClassName('disciplineSelection')[0];
    var optionsArray = getSelectedOptions(compSelectForm);
    var loader = addLoader(thisPanel);
    $.ajax({
      url: './modules/helpers/getSelectedDisciplines.php?disId=<?php echo isset($disId) ? $disId : ''; ?>&compId=<?php echo isset($compId) ? $compId : ''; ?>&memberId=<?php echo isset($memberId) ? $memberId : ''; ?>',
      type: 'post',
      data: {
        "selectedComps": $(compSelectForm).val()
      },
      success: function(response) {
        disSelectForm.innerHTML = response;
        $(loader).remove();
      }
    });
  }

  function MOVEMEMBERsubmitForm(element) {
    var thisPanel = element.closest('.floatingPanel');
    var memberForm = thisPanel.getElementsByClassName('moveMemberForm')[0];
    var panelContent = thisPanel.getElementsByClassName('panelContent')[0];
    var loader = addLoader(thisPanel);
    $.ajax({
      'url': './modules/helpers/moveMembers.php',
      'type': 'post',
      'data': $(memberForm).serializeArray(),
      success: function(result) {
        console.log(result);
        if (result == "success") {

          panelContent.innerHTML = "Members copied! <br> <button OnClick='closePanel(\"" + thisPanel.id + "\")' class='btn btn-primary'>close window</button>";



        } else {
          popMessage('Error', result, function() {}, true);
        }
        $(loader).remove();
      }
    });
  }


  function getSelectedOptions(element) {
    // validate element
    if (!element || !element.options)
      return [];

    // return HTML5 implementation of selectedOptions.
    if (element.selectedOptions)
      return element.selectedOptions;

    // do this if browser doesn't have the HTML5 selectedOptions
    var opts = element.options;
    var selectedOptions = [];
    for (var i = 0; i < opts.length; i++) {
      if (opts[i].selected) {
        selectedOptions.push(opts[i]);
      }
    }
    return selectedOptions;
  }
</script>
<form class="moveMemberForm" name="moveMemberForm" onSubmit="MOVEMEMBERsubmitForm(this)" action="javascript:void(0);">
  <input type="hidden" name="go" value="1">
  <?php
  foreach ($selectedMembers as $value) {
    echo '<input type="hidden" name="memberIdSelected[]" value="' . $value . '">';
  }
  ?>
  <div class="form-group">
    <label for="compSelect">Competitions</label>
    <select onChange="MOVEMEMBERupdateDisciplines(this)" multiple name="competitions[]" class="form-control competitionSelection" id="compSelect">
      <?php
      $stmt = $db->prepare("SELECT * FROM competitions WHERE environmentId LIKE ? AND hide LIKE ? ORDER BY `date` DESC");
      $stmt->execute(array($_SESSION['environmentId'], 0));
      $selectedComps = array();
      while ($compRow = $stmt->fetch(PDO::FETCH_ASSOC)) //Get all Competitions associated with the current Envionment Id
      {
      ?>
        <option value="<?php echo $compRow['id']; ?>"><?php echo $compRow['title']; ?></option>
      <?php
      }
      ?>
    </select>
  </div>
  <div class="form-group">
    <label for="disSelect">Diciplines</label>
    <select multiple name="disciplines[]" class="form-control disciplineSelection" id="disSelect">
      <?php include("./getSelectedDisciplines.php"); ?>
    </select>
  </div>
  <div class="form-group">
    <button class="btn btn-primary" type="submit">Copy <?php echo count($selectedMembers); ?> Members</button>
  </div>

</form>