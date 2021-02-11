<?php 
require_once("includes/header.php"); 
require_once("classes/SearchResults.php"); 

if(!isset($_GET["term"]) || $_GET["term"] == "")
{
    echo "You must enter a search term!";
    exit();
}

$term = $_GET["term"];

if(!isset($_GET["orderBy"]) || $_GET["orderBy"] == "views")
{
    $orderBy = "views";
}
else
{
    $orderBy = "uploadDate";
}

$searchResults = new SearchResults($con, $userLoggedInObj);
$videos = $searchResults->getVideos($term, $orderBy);

$videoGrid = new VideoGrid($con, $userLoggedInObj);


?>
<div class="largeVideoGridContainer">

    <?php
        if(sizeof($videos) > 0)
        {
            echo $videoGrid->createLarge($videos, sizeof($videos) . " results found", true);
        }
        else
        {
            echo "No results found!";
        }
    ?>
    
</div>
<?php 
require_once("includes/footer.php"); 
?>