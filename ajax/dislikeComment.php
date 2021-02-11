<?php 
require_once("../config.php");
require_once("../classes/Comment.php");
require_once("../classes/User.php");

$videoId = $_POST["videoId"];
$username = $_SESSION["userLoggedIn"];
$commentId = $_POST["commentId"];
$userLoggedInObj = new User($con, $username);
$comment = new Comment($con, $commentId, $userLoggedInObj, $videoId);

echo $comment->dislike();

?>