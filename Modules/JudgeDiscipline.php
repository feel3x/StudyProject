<?php


$panelId = isset($_GET['panelId'])?$_GET['panelId']:null;
  ?>
<script type = "text/javascript">
function JUDGEDISCIPLINErefresh<?php echo $panelId; ?>()
{
    refreshingContent["<?php echo $panelId; ?>"] = setTimeout(function() { refreshPanel("<?php echo $panelId; ?>"); }, 10000);
}

   
JUDGEDISCIPLINErefresh<?php echo $panelId; ?>();

function JUDGEDISCIPLINEsubmitScore(element, athleteId, disciplineId, round, criteria)
{
    var panel = getPanel(element);
    scoreValue = panel.getElementsByClassName('scoring'+criteria)[0].value;
    var postArray = {'submittedMemberId':athleteId, 'submittedRound': round, 'submittedCriteria':criteria, 'submittedScore':scoreValue, 'submittedDisciplineId':disciplineId};

    refreshPanel(panel.id, postArray, true);
}
function JUDGEDISCIPLINErevokeScore(element, athleteId, disciplineId, round, scoreId)
{
    var panel = getPanel(element);
    var postArray = {'submittedMemberId':athleteId, 'submittedRound': round, 'revokedScoreId':scoreId, 'submittedDisciplineId':disciplineId};

    refreshPanel(panel.id, postArray, true);
}
</script>
<?php include("../includeSafety.php");

if (isset($_GET['disId'])) {
    $disId = $_GET['disId'];
}

if (in_array(11, $_SESSION['userPermissions'])) {
    $stmt = $db->prepare("SELECT disciplines.id FROM disciplines WHERE disciplines.id LIKE ?");
    $stmt->execute(array($disId));
} else {
    $userId = $_SESSION['userId'];
    $userIdInCommas = "," . $userId . ",";
    $searchUserId = "%$userIdInCommas%";
    $stmt = $db->prepare("SELECT disciplines.id FROM disciplines WHERE disciplines.id LIKE ? AND associatedMembers LIKE ?");
    $stmt->execute(array($disId, $searchUserId));
}
$disRow = $stmt->fetch();
if (!in_array(7, $_SESSION['userPermissions']) || $disRow['id'] == null) {
    echo "Something went wrong";
    exit;
}
//got permissions to show

$stmt = $db->prepare("SELECT judging.criteria AS judgeCriteria, judging.range AS judgeRange, executediscipline.state AS disState, scores.value AS scoreValue, scores.id AS scoreId, executediscipline.currentRound AS currentRound, scores.unixTimestamp AS scoreTime, concat(user.firstName, ' ', user.lastName) AS athleteName, user.id AS athleteId FROM judging LEFT JOIN executediscipline ON executediscipline.disciplineId LIKE judging.disciplineId LEFT JOIN user ON user.id LIKE executediscipline.currentAthlete LEFT JOIN scores ON scores.disciplineId LIKE judging.disciplineId AND scores.competitorId LIKE executediscipline.currentAthlete AND scores.round LIKE executediscipline.currentRound AND scores.judgeId LIKE judging.userId  AND scores.criteria LIKE judging.criteria WHERE judging.userId LIKE ? AND executediscipline.disciplineId LIKE ? ORDER BY judging.criteria");
$stmt->execute(array($_SESSION['userId'], $disId));
$infoArray = [];
while ($infoRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $infoArray[] = $infoRow;
}

if(isset($_POST['submittedMemberId']) && $_POST['submittedMemberId'] == $infoArray[0]['athleteId'] && $infoArray[0]['disState'] == 0 && $infoArray[0]['currentRound'] == $_POST['submittedRound'] && $disId == $_POST['submittedDisciplineId'])
{
    //score submitted or revoked
    if(isset($_POST['submittedScore']) && $_POST['submittedScore'] != "")
    {
        //score submitted
        $eintrag = $db->prepare("INSERT INTO scores (disciplineId, competitorId, `round`, judgeId, `criteria`, `value`) VALUES (?, ?, ?, ?, ?, ?)");
        $eintrag->execute(array($_POST['submittedDisciplineId'], $_POST['submittedMemberId'], $_POST['submittedRound'], $_SESSION['userId'], $_POST['submittedCriteria'], $_POST['submittedScore'])) ;     
    }
    else if(isset($_POST['revokedScoreId']) && $_POST['revokedScoreId'] != "")
    {
        //score revoked
        $eintrag = $db->prepare("DELETE FROM scores WHERE id LIKE ?");
        $eintrag->execute(array($_POST['revokedScoreId'])) ;     
    }
    $stmt = $db->prepare("SELECT judging.criteria AS judgeCriteria, judging.range AS judgeRange, executediscipline.state AS disState, scores.value AS scoreValue, scores.id AS scoreId, executediscipline.currentRound AS currentRound, scores.unixTimestamp AS scoreTime, concat(user.firstName, ' ', user.lastName) AS athleteName, user.id AS athleteId FROM judging LEFT JOIN executediscipline ON executediscipline.disciplineId LIKE judging.disciplineId LEFT JOIN user ON user.id LIKE executediscipline.currentAthlete LEFT JOIN scores ON scores.disciplineId LIKE judging.disciplineId AND scores.competitorId LIKE executediscipline.currentAthlete AND scores.round LIKE executediscipline.currentRound AND scores.judgeId LIKE judging.userId  AND scores.criteria LIKE judging.criteria WHERE judging.userId LIKE ? AND executediscipline.disciplineId LIKE ? ORDER BY judging.criteria");
    $stmt->execute(array($_SESSION['userId'], $disId));
    $infoArray = [];
    while ($infoRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $infoArray[] = $infoRow;
    }
}
?>
<div id="thisPage"></div>
<?php
if (count($infoArray) == 0) {
    //discipline hasn't started
?>
    <div class="d-flex h-100 align-items-center justify-content-center">
        <div class="p-2 bd-highlight col-example text-secondary">Discipline has not started. Please wait..
            <div>
                <div class="loadingDiv position-relative">
                    <div class="loadingBody firstLine"></div>
                    <div class="loadingBody secondLine"></div>
                    <div class="loadingBody thirdLine"></div>
                </div>
            </div>
        </div>
    </div>
<?php
    exit;
} else if ($infoArray[0]['disState'] == 1) {
    //discipline is paused
?>
    <div class="d-flex h-100 align-items-center justify-content-center">
        <div class="p-2 bd-highlight col-example text-secondary">Discipline is paused. Please wait..
            <div>
                <div class="loadingDiv position-relative">
                    <div class="loadingBody firstLine"></div>
                    <div class="loadingBody secondLine"></div>
                    <div class="loadingBody thirdLine"></div>
                </div>
            </div>
        </div>
    </div>
<?php
    exit;
}
?>

<div class="d-flex justify-content-center">
    <div class="row">
        <div class="col-sm text-center border border-primary rounded m-2 bg-dark text-white">
            <?php echo $infoArray[0]['athleteName']; ?>
        </div>
    </div>
</div>
<div class="d-flex h-90 align-items-center justify-content-center">
    <div class="row border border-primary rounded">
        <?php
        for ($i = 0; $i < count($infoArray); $i++) {
            $rangeInfo = explode("-", $infoArray[$i]['judgeRange']);
        ?>
            <div class="col-sm text-center">
                <div class="border border-primary rounded m-2 bg-dark text-white">
                    <b><?php echo $infoArray[$i]['judgeCriteria']; ?></b><br>
                    <input name="indication<?php echo $infoArray[$i]['judgeCriteria']; ?>" disabled class="bg-<?php echo $infoArray[$i]['scoreValue'] === null ? "primary" : "success"; ?> text-white rounded px-3 text-center" value="<?php echo $infoArray[$i]['scoreValue'] === null ? ($rangeInfo[0]+$rangeInfo[1])/2 : $infoArray[$i]['scoreValue']; ?>"><br>
                    <input name="scoring<?php echo $infoArray[$i]['judgeCriteria']; ?>" class="scoring<?php echo $infoArray[$i]['judgeCriteria']; ?>" <?php echo $infoArray[$i]['scoreValue'] === null ? "" : "value=\"".$infoArray[$i]['scoreValue']."\""; ?> min="<?php echo $rangeInfo[0]; ?>" max="<?php echo $rangeInfo[1]; ?>" step="<?php echo $rangeInfo[2]; ?>" <?php echo $infoArray[$i]['scoreValue'] === null ? "" : "disabled"; ?> class="w-100" type="range" oninput="this.closest('div').querySelector('[name=indication<?php echo $infoArray[$i]['judgeCriteria']; ?>]').value = this.value">
                </div>
                <?php 
                $buttonOnClick = "";
                if($infoArray[$i]['scoreValue'] === null)
                {
                    $buttonOnClick = "JUDGEDISCIPLINEsubmitScore(this,".$infoArray[$i]['athleteId'].",".$disId.",".$infoArray[$i]['currentRound'].",'".$infoArray[$i]['judgeCriteria']."')"; 
                }
                else
                {
                    $buttonOnClick = "JUDGEDISCIPLINErevokeScore(this,".$infoArray[$i]['athleteId'].",".$disId.",".$infoArray[$i]['currentRound'].",".$infoArray[$i]['scoreId'].")"; 
                }
                ?>
                <button onClick="<?php echo $buttonOnClick; ?>" class="btn btn-<?php echo $infoArray[$i]['scoreValue'] === null ? "primary" : "danger"; ?> m-1"><?php echo $infoArray[$i]['scoreValue'] === null ? "Submit" : "Revoke"; ?></button>
            </div>
        <?php
        }
        ?>
    </div>
</div>