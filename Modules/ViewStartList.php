<?php
/**
 * PHP script module to display a start list of competitors for a discipline.
 */

include("../includeSafety.php");

if (isset($_GET['disId'])) {
  $disId = $_GET['disId'];
}

//Competition Information
  $stmt = $db->prepare("SELECT disciplines.* FROM disciplines LEFT JOIN competitions ON competitions.id LIKE disciplines.compId WHERE disciplines.id LIKE ? ORDER BY `date` DESC");
  $stmt->execute(array($disId));
$disRow = $stmt->fetch();

if ($disRow['id'] == null) {
  echo "Something went wrong";
  exit;
}

//Current Athlete Info in order to highlight the Athlete currently performing
$stmt = $db->prepare("SELECT currentAthlete FROM executediscipline WHERE disciplineId LIKE ? AND `state` LIKE ?");
$stmt->execute(array($disId, "0"));
$execDisInfo = $stmt->fetch();

?>
<title class="title"><?php echo $disRow['title']; ?> - Startlist</title>

<?php
$panelId = isset($_GET['panelId']) ? $_GET['panelId'] : null;
?>

<script>
  function VIEWSTARTLISTrefresh<?php echo $panelId; ?>(element) {
    refreshingContent["<?php echo $panelId; ?>"] = setTimeout(function() {
      refreshPanel("<?php echo $panelId; ?>");
    }, 10000);
  }

  VIEWSTARTLISTrefresh<?php echo $panelId; ?>();
</script>

<div class="container">
  <div class="row w-100">
    <div class="col-sm"><a href="#" Onclick="printContent(this)"><i class="fas fa-print"></i></a></div>
  </div>
</div>
<div id="thisPage" class="container sectionToPrint">
  <table style="font-size: inherit; opacity:1;" id="" class="table table-dark table-striped text-light">
    <thead style="background-color: black; color: white; position: sticky;top: 0">
      <tr style="position: sticky;top: 0">
        <th style="position: sticky;top: 0">#</th>
        <th style="position: sticky;top: 0">Name</th>
      </tr style="position: sticky;top: 0">
    </thead>
    <tbody>

      <?php
//Figure out how the score is calculated in this competition
      function getScoreString($ruleset)
      {
        preg_match_all("/([a-z]*?\([a-z]*?\))/", $ruleset, $matches);
        $matches = $matches[0];
        for ($i = 0; $i < count($matches); $i++) {
          $data = explode("(", $matches[$i]);
          $replacement = $data[0] . "(CASE WHEN criteria = '" . str_replace(")", "", $data[1]) . "' THEN value ELSE 0 end)";
          $ruleset = str_replace($matches[$i], $replacement, $ruleset);
        }
        return $ruleset;
      }

      $competitors = str_replace(',', '', explode(',,', $disRow['associatedMembers']));
      $in  = str_repeat('?,', count($competitors) - 1) . '?';
      $newSQL = "";
      
      //different kind of Start Listings
      switch (explode(":", $disRow['startListSort'])[0]) {
        case "random":
          $orderBy = "rand(1)";
          break;
        case "scoreOfId":
          $stmt = $db->prepare("SELECT criteriaRuleset FROM disciplines WHERE id LIKE ?");
          $sortData = explode(":", $disRow['startListSort']);
          $stmt->execute(array($sortData[1]));
          $tempRow = $stmt->fetch();
          $newSQL = "SELECT *, (SELECT " . getScoreString($tempRow['criteriaRuleset']) . " 
          FROM scores WHERE disciplineId LIKE ? AND competitorId LIKE user.id GROUP BY competitorId) AS score FROM user WHERE id in($in) AND `permissions` LIKE '%,9,%' ORDER BY score " . $sortData[2] . ", lastName ";
          break;
        default:
          $orderBy = $disRow['startListSort'];
          break;
      }
      if ($newSQL == "") {
        $stmt = $db->prepare("SELECT * FROM user WHERE id in($in) AND `permissions` LIKE '%,9,%' ORDER BY $orderBy");
        $stmt->execute($competitors);
      } else {
        $stmt = $db->prepare($newSQL);
        $stmt->execute(array_merge(array(explode(":", $disRow['startListSort'])[1]), $competitors));
      }

      //List Competitors
      $startNumber = 0;
      while ($startRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $startNumber++;
      ?>

        <tr class="<?php if ($execDisInfo != false && $execDisInfo['currentAthlete'] == $startRow['id']) {
                      echo 'table-success text-dark';
                    } ?>">
          <td style="opacity:1">
            <?php echo $startNumber; ?></td>
          <td style="opacity:1;"><?php echo $startRow['firstName'] . " " . $startRow['lastName']; ?></td>
        </tr>

      <?php
      }

      ?>

    </tbody>
  </table>
</div>