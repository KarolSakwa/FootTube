<?php require_once("config.php");
require_once("classes/User.php");
require_once("classes/Button.php");
require_once("classes/Video.php");
require_once("classes/VideoGrid.php");
require_once("classes/VideoGridItem.php");
require_once("classes/Subscriptions.php");
require_once("classes/NavigationMenu.php");
require_once("classes/NavigationMenuSmall.php");



$usernameLoggedIn = User::isLoggedIn() ? $_SESSION["userLoggedIn"] : "";
$userLoggedInObj = new User($con, $usernameLoggedIn);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>FootTube</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="static/css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script src="static/js/commonActions.js"></script>
        <script src="static/js/userActions.js"></script>
    </head>
        <body>
            <div id="pageContainer">
                <div id="mastHeadContainer">
                    <button class="navShowHide">
                        <img src="static/img/icons/menu.png" />
                    </button>
                    <a class="logoContainer" href="index.php">
                        <img src="static/img/icons/FootTube logo.png" />
                    </a>

                    <div class="searchBarContainer">
                        <form action="search.php" method="GET">
                            <input type="text" name="term" class="searchBar" placeholder="Search...">
                            <button class="searchButton">
                                <img src="static/img/icons/search.png" />
                            </button>
                        </form>
                    </div>
                    <div class="rightIcons">
                        <a href="upload.php">
                            <img class="upload" src="static/img/icons/upload.png" alt="upload">
                        </a>
                        <?php echo Button::createUserProfileNavigationButton($con, $userLoggedInObj->getUsername()); ?>
                    </div>
                </div>
                <div id="sideNavContainer" style="display:none; padding-left: 0;">
                    <?php
                        $navigationMenu = new NavigationMenu($con, $userLoggedInObj);
                        echo $navigationMenu->create();
                    ?>
                </div>
                
                <div id="mainSectionContainer">
				<div id="mainContentContainer">
