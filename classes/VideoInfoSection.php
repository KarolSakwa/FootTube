<?php 
require_once("VideoInfoControls.php");

class VideoInfoSection
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
		return $this->createPrimarySection() . $this->createSecondarySection();
	}
	
	private function createPrimarySection()
	{
		$title = $this->video->getTitle();
		$views = $this->video->getViews();
		$videoControls = new VideoInfoControls($this->con, $this->video, $this->userLoggedInObj);
		$controls = $videoControls->create();
		
		return "
		<div class='videoInfo'>
			<h1>$title</h1>
			<div class='bottomSection'>
				<span class='viewCount'>
				$views views
				</span>
				$controls
			</div>
		</div>
		";
	}
	
	private function createSecondarySection()
	{
		$description = $this->video->getDescription();
		$uploadDate = $this->video->getUploadDate();
		$uploadedBy = $this->video->getUploadedBy();
		$profileButton = Button::createUserProfileButton($this->con, $uploadedBy);
		
		// checking wheter the logged user is owner of the video
		
		if($uploadedBy == $this->userLoggedInObj->getUsername())
		{
			$actionButton = Button::createEditVideoButton($this->video->getId());
		}
		else
		{
			$userToObj = new User($this->con, $uploadedBy);
			$actionButton = Button::createSubscriberButton($this->con, $userToObj, $this->userLoggedInObj);
		}
		return "<div class='secondaryInfo'>
		<div class='topRow'>
			$profileButton

			<div class='uploadInfo'>
				<span class='owner'>
					<a href='profile.php?username=$uploadedBy'>
						$uploadedBy
					</a>
				</span>
				<span class='date'>Published on $uploadDate</span>
			</div>
			$actionButton
		</div>
		<div class='descriptionContainer'>
		$description
		</div>

	</div>";
	}
}

?>