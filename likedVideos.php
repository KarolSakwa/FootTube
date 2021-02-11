<?php 
require_once("includes/header.php");
require_once("classes/LikedVideos.php");
include_once('includes/sidenavsmall.php');

if(!User::isLoggedIn())
{
    header("Location: logIn.php");
}

$likedVideos = new LikedVideos($con, $userLoggedInObj);
$videos = $likedVideos->getVideos();

$videoGrid = new VideoGrid($con, $userLoggedInObj);
?>

<div class="largeVideoGridContainer">
    <?php
    if(sizeof($videos) > 0)
    {
        echo $videoGrid->createLarge($videos, "Videos you like", false);
    }
    else
    {
        echo "No videos to show";
    }
    ?>
</div>