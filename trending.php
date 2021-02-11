<?php 
require_once("includes/header.php");
require_once("classes/Trending.php");
include_once('includes/sidenavsmall.php');


$trending = new Trending($con, $userLoggedInObj);
$videos = $trending->getVideos();

$videoGrid = new VideoGrid($con, $userLoggedInObj);
?>

<div class="largeVideoGridContainer">
    <?php
    if(sizeof($videos) > 0)
    {
        echo $videoGrid->createLarge($videos, "Trending videos uploaded in the last week", false);
    }
    else
    {
        echo "No trending videos to show";
    }
    ?>
</div>