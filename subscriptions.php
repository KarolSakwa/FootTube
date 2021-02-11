<?php 
require_once("includes/header.php");

if(!User::isLoggedIn())
{
    header("Location: logIn.php");
}

$subscriptions = new Subscriptions($con, $userLoggedInObj);
$videos = $subscriptions->getVideos();

$videoGrid = new VideoGrid($con, $userLoggedInObj);
?>

<div class="largeVideoGridContainer">
    <?php
    if(sizeof($videos) > 0)
    {
        echo $videoGrid->createLarge($videos, "New from your subscriptions", false);
    }
    else
    {
        echo "No videos to show";
    }
    ?>
</div>