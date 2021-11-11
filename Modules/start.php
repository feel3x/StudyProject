<?php
include("../connect.php");
$rootPage = true; //Modules can only be accesed when included into this page
if(isset($_GET['id']))
{
  $siteId = $_GET['id'];
}
else
{
  $siteId = 1;
}
$stmt = $db->prepare("SELECT * FROM sites WHERE id LIKE ?");
$stmt->execute(array($siteId));
$siteRow = $stmt->fetch();
$isPermitted = false;
$sitePermissions = str_replace(',', '', explode(',,', $siteRow['permissions']));

    for($i = 0; $i < count($sitePermissions); $i++)
    {
      if(in_array($sitePermissions[$i], $_SESSION['userPermissions']))
      {
        $isPermitted = true;
      }
    }
    if(!$isPermitted)
    {
      echo "You do not have permissions for this content. Please ask an Administrator about this issue.";
    }
    else
    {
    
      //Site is permitted to show
      ?>
      <title><?php echo $siteRow['title']; ?></title>
      <div class="container">
  <div class="row">
     

    <?php
    $siteIdInCommas = ",".$siteId.",";
    $searchSiteId ="%$siteIdInCommas%";
      $stmt = $db->prepare("SELECT * FROM buttons WHERE siteId LIKE ?");
      $stmt->execute(array($searchSiteId));
      while($buttonRow = $stmt->fetch(PDO::FETCH_ASSOC))
      {
        $isPermitted = false;
        $buttonPermissions = str_replace(',', '', explode(',,', $buttonRow['permissions']));
        for($i = 0; $i < count($buttonPermissions); $i++)
        {
          if(in_array($buttonPermissions[$i], $_SESSION['userPermissions']))
          {
            $isPermitted = true;
          }
  
        }
        if($isPermitted)
        {
         //Button is permitted to show
         $buttonTitle = $buttonRow['title'];
         $neededGetParameters = explode(",", $buttonRow['neededGetParameters']);
         $neededPostParameters = explode(",", $buttonRow['neededPostParameters']);
         $getParameters = "";
         $postParameters = "";
         for($i = 0; $i< count($neededGetParameters); $i++)
         {
           if(isset($_GET[$neededGetParameters[$i]]))
           {
             $getParameters = $getParameters."&".$neededGetParameters[$i]."=".$_GET[$neededGetParameters[$i]];
           }
         }
         for($i = 0; $i< count($neededPostParameters); $i++)
         {
           if(isset($_GET[$neededPostParameters[$i]]))
           {
             $postParameters = $postParameters."&".$neededPostParameters[$i]."=".$_GET[$neededPostParameters[$i]];
           }
         }
         if($buttonRow['openSeperateWindow'] == 1)
         {
           $onClick = "createPanel('modules/start.php?id=".$buttonRow['targetSiteId']."".$getParameters."')";
         }
         else
         {
           $onClick = "goTo(this, 'modules/start.php?id=".$buttonRow['targetSiteId']."".$getParameters."')";
         }
         ?>
         <div onclick="<?php echo $onClick; ?>" class="btn btn-dark col-sm m-1">
         <i class="fas fa-<?php echo $buttonRow['icon']; ?> fa-lg"></i><br>
        <input class="btn btn-dark" type="submit" value="<?php echo $buttonRow['title']; ?>"></input>
        </div> 
        <?php
        } 
      }
?>

</div>
      </div>

<?php 
$siteModules = explode(',',$siteRow['modules']);

for($i = 0; $i<count($siteModules); $i++)
{
  if($siteModules[$i] != '')
  {
  include($siteModules[$i].".php");
  }
}
?>



        <?php } ?>


        







