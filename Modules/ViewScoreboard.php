<?php

/**
 * This script module displays the scoreboard for a specific discipline.
 */

include("../includeSafety.php");
if (isset($_GET['disId'])) {
  $disId = $_GET['disId'];
}

//Competition Informatio
$stmt = $db->prepare("SELECT disciplines.* FROM disciplines LEFT JOIN competitions ON competitions.id LIKE disciplines.compId WHERE disciplines.id LIKE ? ORDER BY `date` DESC");
$stmt->execute(array($disId));
$disRow = $stmt->fetch();

if ($disRow['id'] == null) {
  echo "Something went wrong";
  exit;
}

//Current Athlete Info in order to highlight the Athlete currently performing
$stmt = $db->prepare("SELECT currentAthlete FROM executediscipline WHERE disciplineId LIKE ? AND `state`LIKE ?");
$stmt->execute(array($disId, "0"));
$execDisInfo = $stmt->fetch();
?>

<title class="title"><?php echo $disRow['title']; ?> - Scoreboard</title>
<?php
$panelId = isset($_GET['panelId']) ? $_GET['panelId'] : null;
?>
<script>
  var timeout;

  function VIEWSCOREBOARDrefresh<?php echo $panelId; ?>(element) {


    timeout = setTimeout(function() {
      refreshPanel("<?php echo $panelId; ?>");
    }, 10000);

    refreshingContent["<?php echo $panelId; ?>"] = timeout;

  }
  VIEWSCOREBOARDrefresh<?php echo $panelId; ?>();

  function VIEWSCOREBOARDcheckCheckedBoxes(element) {
    var panel = getPanel(element);
    var checkboxes = panel.getElementsByClassName('memberCheckbox');
    var foundChecked = false;
    for (var i = 0; i < checkboxes.length; i++) {
      if (checkboxes[i].checked) {
        $(panel.getElementsByClassName('memberActionDiv')[0]).slideDown(200);
        foundChecked = true;
        clearTimeout(timeout);
        break;
      }
    }
    //only refresh if no boxes are checked
    if (!foundChecked) {
      VIEWSCOREBOARDrefresh(panel);
      $(panel.getElementsByClassName('memberActionDiv')[0]).slideUp(200);
    }
  }

  //function to move certain members to a new discipline
  function VIEWSCOREBOARDmoveMemberIds(element) {
    var thisPanel = getPanel(element);
    var memberIdForm = thisPanel.getElementsByClassName('memberIdForm')[0];
    console.log($(memberIdForm).serializeArray());
    goTo(element, './modules/helpers/moveMembers.php',
      $(memberIdForm).serializeArray());
  }
</script>
<div style="display:none; z-index:99;" class="btn btn-dark sticky-top memberActionDiv"><?php if (checkPermission("Move Members")) { ?><button onClick="VIEWSCOREBOARDmoveMemberIds(this)" class="btn btn-primary m-2">Copy To</button><?php } ?> </div>

<div class="container">
  <div class="row w-100">
    <div class="col-sm"><a href="#" Onclick="printContent(this)"><i class="fas fa-print"></i></a></div>

  </div>
</div>
<div id="thisPage" class="container sectionToPrint">
  <form class="memberIdForm" name="memberIdForm" action="javascript:void(0);">
    <table stlye="font-size: inherit;" class="table table-dark">
      <thead style="z-index:5;" class="thead-light sticky-top">
        <tr style="position: sticky;top: 0">
          <th style="position: sticky;top: 0">#</th>
          <th style="position: sticky;top: 0">Name</th>
          <th style="position: sticky;top: 0">Score</th>
        </tr style="position: sticky;top: 0">
      </thead>
      <tbody>

        <?php

        //figure out how to calculate the score in this competition
        function getScoreString($ruleset)
        {
          preg_match_all("/([a-z]*?\([a-z]*?\))/", $ruleset, $matches);
          $matches = $matches[0];
          for ($i = 0; $i < count($matches); $i++) {
            $data = explode("(", $matches[$i]);
            $replacement = $data[0] . "(CASE WHEN criteria = '" . str_replace(")", "", $data[1]) . "' THEN value ELSE 0 end)";
            $ruleset = str_replace($matches[$i], $replacement, $ruleset);
          }
          return "(" . $ruleset . ")";
        }

        //the rules for calculating the score in this competition
        $scoreRules = getScoreString($disRow['criteriaRuleset']);

        $individualScores = [];
        //Get individual scores and store them in an array
        for ($i = 0; $i < $disRow['rounds']; $i++) {
          $stmt = $db->prepare("SELECT *, 
  (SELECT $scoreRules
  FROM scores A
  WHERE disciplineId LIKE ? 
  AND `round` LIKE ? 
  AND A.competitorId LIKE B.competitorId 
  GROUP BY competitorId, `round`) AS score FROM scores B WHERE disciplineId LIKE ? AND `round` LIKE ?");
          $stmt->execute(array_merge(array($disId, ($i + 1), $disId, ($i + 1))));
          while ($indScoreRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $individualScores[$i][$indScoreRow['competitorId']][$indScoreRow['criteria']][] = $indScoreRow['value'];
            $individualScores[$i][$indScoreRow['competitorId']]['Score'][0] = $indScoreRow['score'];
          }
        }



        //get criteria of discipline
        $disCriteria = explode(",", $disRow['criteria']);
        $disCriteria[] = 'Score';

        $associatedMembersOfDis = explode(",,", $disRow['associatedMembers']);
        for ($i = 0; $i < count($associatedMembersOfDis); $i++) {
          $associatedMembersOfDis[$i] = str_replace(",", "", $associatedMembersOfDis[$i]);
        }
        $in  = str_repeat('?,', count($associatedMembersOfDis) - 1) . '?';
        $stmt = $db->prepare("SELECT *, (SELECT $scoreRules 
FROM scores WHERE disciplineId LIKE ? AND competitorId LIKE user.id GROUP BY competitorId) AS score FROM user WHERE `permissions` LIKE '%,9,%' AND id IN ($in) ORDER BY score DESC");

        $stmt->execute(array_merge(array($disId), $associatedMembersOfDis));
        $startNumber = 0;
        while ($scoreRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $startNumber++;
        ?>
          <tr class="<?php if ($execDisInfo != false && $execDisInfo['currentAthlete'] == $scoreRow['id']) {
                        echo 'table-success text-dark';
                      } ?>">

            <td> <?php if (checkPermission("Manage Members")) {
                  ?>
                <input onchange="VIEWSCOREBOARDcheckCheckedBoxes(this)" type="checkbox" class="memberCheckbox" name="memberIdSelected[]" value="<?php echo $scoreRow['id']; ?>"></input>
                <?php
                  }
                ?><?php echo $startNumber; ?>
            </td>
            <td>
              <?php echo $scoreRow['firstName'] . " " . $scoreRow['lastName']; ?>
            </td>
            <td><b><?php echo number_format($scoreRow['score'], 2); ?></b></td>
          </tr>
          <tr>
            <td colspan="3" class="p-0">
              <table class="table table-light table-sm w-100 table-bordered">
                <tr>
                  <th></th>
                  <?php foreach ($disCriteria as $c) {
                    echo "<th class='text-center'>" . explode("~", $c)[0] . "</th>";
                  }
                  ?>
                </tr>
                <?php
                for ($i = 0; $i < count($individualScores); $i++) {
                  echo "<tr>";
                  echo   "<td>Round " . ($i + 1) . "</td>";
                  foreach ($disCriteria as $c) {
                    echo "<td><table class='w-100'><tr>";
                    if (array_key_exists($i, $individualScores) && array_key_exists($scoreRow['id'], $individualScores[$i]) && array_key_exists(explode("~", $c)[0], $individualScores[$i][$scoreRow['id']])) {
                      foreach ($individualScores[$i][$scoreRow['id']][explode("~", $c)[0]] as $cValue) {
                        echo "<td class='text-center'>" . number_format($cValue, 2) . "</td>";
                      }
                    }
                    echo "</tr></table></td>";
                  }
                  echo "</tr>";
                }
                ?>
              </table>
            </td>
          </tr>
        <?php
        }

        ?>

      </tbody>
    </table>
  </form>

</div>