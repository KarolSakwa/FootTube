<?php 
require_once("classes/NavigationMenuSmall.php");
?>

<div id="sideNavContainerSmall">
<?php
    $navigationMenuSmall = new NavigationMenuSmall($con, $userLoggedInObj);
    echo $navigationMenuSmall->create();
?>
</div>