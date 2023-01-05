<?php 
//Check to see if the varible $rootPage exists in the current context. If it does, it means this page is included into a page that has the $rootPage variable set.
if(!isset($rootPage) || $rootPage == "")
{
    echo "This page can only be called from within the App";
exit;
}
?>