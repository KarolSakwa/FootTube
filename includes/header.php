<?php require_once("includes/config.php");
require_once("includes/classes/User.php");
require_once("includes/classes/ButtonProvider.php");
require_once('includes/classes/Video.php');
require_once('includes/classes/VideoGrid.php');
require_once('includes/classes/VideoGridItem.php');
require_once('includes/classes/SubscriptionsProvider.php');
require_once('includes/classes/NavigationMenuProvider.php');
require_once('includes/classes/NavigationMenuSmallProvider.php');



$usernameLoggedIn = User::isLoggedIn() ? $_SESSION["userLoggedIn"] : "";
$userLoggedInObj = new User($con, $usernameLoggedIn);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>FootTube</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script src="assets/js/commonActions.js"></script>
        <script src="assets/js/userActions.js"></script>
    </head>
        <body>
            <div id="pageContainer">
                <div id="mastHeadContainer">
                    <button class="navShowHide">
                        <img src="assets/images/icons/menu.png" />
                    </button>
                    <a class="logoContainer" href="index.php">
                        <img src="assets/images/icons/FootTube logo.png" />
                    </a>

                    <div class="searchBarContainer">
                        <form action="search.php" method="GET">
                            <input type="text" name="term" class="searchBar" placeholder="Search...">
                            <button class="searchButton">
                                <img src="assets/images/icons/search.png" />
                            </button>
                        </form>
                    </div>
                    <div class="rightIcons">
                        <a href="upload.php">
                            <img class="upload" src="assets/images/icons/upload.png" alt="upload">
                        </a>
                        <?php echo ButtonProvider::createUserProfileNavigationButton($con, $userLoggedInObj->getUsername()); ?>
                    </div>
                </div>
                <div id="sideNavContainer" style="display:none;">
                    <?php
                        $navigationProvider = new NavigationMenuProvider($con, $userLoggedInObj);
                        echo $navigationProvider->create();
                    ?>
                </div>
                
                <div id="mainSectionContainer">
				<div id="mainContentContainer">
