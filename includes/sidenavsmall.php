<?php 
require_once('includes/classes/NavigationMenuSmallProvider.php');
?>

<div id="sideNavContainerSmall">
<?php
    $navigationProvider = new NavigationMenuSmallProvider($con, $userLoggedInObj);
    echo $navigationProvider->create();
?>
</div>