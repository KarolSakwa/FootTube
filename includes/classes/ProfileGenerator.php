<?php
require_once("ProfileData.php");
require_once("User.php");
require_once("C:/xampp/htdocs/FootTube/includes/config.php");
class ProfileGenerator {

    private $con, $userLoggedInObj, $profileData;

    public function __construct($con, $userLoggedInObj, $profileUsername) {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
        $this->profileData = new ProfileData($con, $profileUsername);
    }

    public function create() 
    {
        $profileUsername = $this->profileData->getProfileUsername();
        
        if(!$this->profileData->userExists())
        {
            return "User do not exist!";
        }

    $coverPhotoSection = $this->createCoverPhotoSection();
    $headerSection = $this->createHeaderSection();
    $tabsSection = $this->createTabsSection();
    $contentSection = $this->createContentSection();
    $newCoverPhoto = $this->updateCoverPhoto($this->userLoggedInObj->getUsername());

    return "<div class='profileContainer'>
                $coverPhotoSection
                $headerSection
                $tabsSection
                $contentSection
                $newCoverPhoto
            </div>";
    }

    public function createCoverPhotoSection()
    {
		$profileDataUsername = $this->profileData->getProfileUsername();
		$userLoggedInObjUsername = $this->userLoggedInObj->getUsername();
		$coverPhotoObj = $this->profileData->getCoverPhoto();
		$editButtonDisplay = $profileDataUsername == $userLoggedInObjUsername ? "style='display:block;'" : "style='display:none;'";
        $name = $this->profileData->getProfileUserFullName();
        return "<div class='coverPhotoContainer'>
					<img src='$coverPhotoObj' class='coverPhoto'>
					<div class='editCoverPhotoButton' <?php $editButtonDisplay ;?>
                        <form action='' method='POST' enctype='multipart/form-data'>
                            <input type='file' onchange='this.form.submit()' name='coverPhoto' >
                        </form>
                    </div>
                </div>";
    }

    public function setCoverPhoto()
    {
		$target_dir = "uploads/coverPhoto/";
		$coverPhotoPath = $target_dir . basename($_FILES["coverPhoto"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($coverPhotoPath,PATHINFO_EXTENSION));
		$check = getimagesize($_FILES["coverPhoto"]["tmp_name"]);
		if($check != true) {
			echo "File is not an image.";
			$uploadOk = 0;
		}
		if ($_FILES["coverPhoto"]["size"] > 5000000000) {
			echo "Sorry, your file is too large.";
			$uploadOk = 0;
		}
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) 
		{
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
		}
		if ($uploadOk == 0) 
		{
			echo "Sorry, your file was not uploaded.";
		}
		if (!move_uploaded_file($_FILES["coverPhoto"]["tmp_name"], $coverPhotoPath))
		{
			echo "Sorry, there was an error uploading your file.";
		}
		header("Refresh:0");
		return $coverPhotoPath;
    }

    public function updateCoverPhoto($username)
    {
        if(isset($_FILES['coverPhoto']))
        {
            $newCoverPhotoPath = $this->setCoverPhoto();
            $query = $this->con->prepare("

            UPDATE users SET coverPhoto=:coverPhoto WHERE username=:username
            
            ");
            
            $query->bindParam(":coverPhoto", $newCoverPhotoPath); 
            $query->bindParam(":username", $username); 
            
            
            return $query->execute();
        }
    }
    
    public function createHeaderSection()
    {
        $profileImage = $this->profileData->getProfilePic();
		$name = $this->profileData->getProfileUserFullName();
        $subsCount = $this->profileData->getSubscriberCount();
        
        $button = $this->createHeaderButton();
		
		return "<div class='profileHeader'>
					<div class='userInfoContainer'>
						<img class='profileImage' src='$profileImage'>
						<div class='userInfo'>
							<span class='title'>$name</span>
							<span class='subscriberCount'>$subsCount subscribers</span>
						</div>
					</div>
                    <div class='buttonContainer'>
                        <div class='buttonItem'>
                            $button 
                        </div>
					</div>
				</div>";
    }
    
    public function createTabsSection()
    {
        return "<ul class='nav nav-tabs' role='tablist'>
				    <li class='nav-item'>
					<a class='nav-link active' id='videos-tab' data-toggle='tab' href='#videos' role='tab' aria-controls='videos' aria-selected='true'>VIDEOS</a>
				    </li>
				    <li class='nav-item'>
					<a class='nav-link' id='about-tab' data-toggle='tab' href='#about' role='tab' aria-controls='about' aria-selected='false'>ABOUT</a>
                    </li>
                    <li class='nav-item'>
					<a class='nav-link' id='comments-tab' data-toggle='tab' href='#comments' role='tab' aria-controls='comments' aria-selected='false'>COMMENTS</a>
				    </li>
				</ul>";
    }
    
    public function createContentSection()
    {
		$videos = $this->profileData->getUsersVideos();
		
		if(sizeof($videos) > 0)
		{
			$videoGrid = new VideoGrid($this->con, $this->userLoggedInObj);
			$videoGridHtml = $videoGrid->create($videos, null, false);
		}
		else
		{
			$videoGridHtml = "<span>This user has no videos</span>";
		}
		
        $aboutSection = $this->createAboutSection();
        $commentsSection = $this->createCommentsSection();
		
        return "<div class='tab-content channelContent'>
				    <div class='tab-pane fade show active' id='videos' role='tabpanel' aria-labelledby='videos-tab'>
					$videoGridHtml
				    </div>
				    <div class='tab-pane fade' id='about' role='tabpanel' aria-labelledby='about-tab'>
					$aboutSection
                    </div>
                    <div class='tab-pane fade' id='comments' role='tabpanel' aria-labelledby='comments-tab'>
					$commentsSection
				    </div>";
    }

    private function createHeaderButton()
    {
        if($this->userLoggedInObj->getUsername() == $this->profileData->getProfileUsername())
        {
            return "";
        }
        else
        {
            return ButtonProvider::createSubscriberButton($this->con, $this->profileData->getProfileUserObj(), $this->userLoggedInObj);
        }
    }
	
	private function createAboutSection()
	{
		$html = "<div class='section'>
					<div class='title'>
						<span>Details</span>
					</div>
					<div class='values'>";
				
		$details = $this->profileData->getAllUserDetails();
		foreach($details as $key => $value)
		{
			$html .= "<span>$key: $value</span>";
		}
				
		$html .= "</div></div>";
		
		return $html;
    }
    private function createCommentsSection()
	{
		$html = "<div class='section'>
					<div class='title'>
						<span>Comments</span>
					</div>
					<div class='values'>";
				
		$commentBody = $this->profileData->getUserComments();
				
		
		
		return $commentBody;
	}
}
?>