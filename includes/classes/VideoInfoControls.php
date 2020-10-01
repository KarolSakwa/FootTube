<?php 
require_once("includes/classes/ButtonProvider.php");

class VideoInfoControls
{
	private $con, $video, $userLoggedInObj;
	
	public function __construct($con, $video, $userLoggedInObj)
	{
		$this->con = $con;
		$this->video = $video;
		$this->userLoggedInObj = $userLoggedInObj;
	}
	
	public function create()
	{
		$likeButton = $this->createLikeButton();
		$dislikeButton = $this->createDislikeButton();
		
		return "
		
		<div class='controls'>
			$likeButton
			$dislikeButton
		</div>
		
		";
	}
	
	private function createLikeButton()
	{
		$text = $this->video->getLikes();
		$class = "likeButton";
		$imageSrc = "assets/images/icons/thumb-up.png";
		$videoId = $this->video->getId();
		$action = "likeVideo(this, $videoId)";
		
		if($this->video->wasLikedBy())
		{
			$imageSrc = "assets/images/icons/thumb-up-active.png";
		}
		
		return ButtonProvider::createButton($text, $class, $imageSrc, $action);
	}
	private function createDislikeButton()
	{
		$text = $this->video->getDislikes();
		$class = "dislikeButton";
		$imageSrc = "assets/images/icons/thumb-down.png";
		$videoId = $this->video->getId();
		$action = "dislikeVideo(this, $videoId)";
		
		if($this->video->wasDislikedBy())
		{
			$imageSrc = "assets/images/icons/thumb-down-active.png";
		}
		
		return ButtonProvider::createButton($text, $class, $imageSrc, $action);
	}
	
	
}

?>