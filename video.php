<?php 
require_once("includes/header.php"); 
require_once("classes/VideoPlayer.php"); 
require_once("classes/VideoInfoSection.php"); 
require_once("classes/Comment.php"); 
require_once("classes/CommentSection.php"); 

if(!isset($_GET["id"]))
{
	echo "No ID passed to the watch page";
	exit(); 
}
$video = new Video($con, $_GET["id"], $userLoggedInObj);
$video->incrementViews();

?>

<script src="static/js/videoPlayerActions.js"></script>
<script src="static/js/commentActions.js"></script>

<div class="watchLeftColumn">

<?php 

$videoPlayer = new VideoPlayer($video);

echo $videoPlayer->create(true);

$videoInfoSection = new VideoInfoSection($con, $video, $userLoggedInObj);
echo $videoInfoSection->create();

$commentSection = new CommentSection($con, $video, $userLoggedInObj);
echo $commentSection->create();

?>
</div>
<div class="suggestions">
	<?php
	$videoGrid = new VideoGrid($con, $userLoggedInObj);
	echo $videoGrid->create(null, null, false);
	?>
</div>


<?php include_once('includes/footer.php');?>
