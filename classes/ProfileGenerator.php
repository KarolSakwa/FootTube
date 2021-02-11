<?php
require_once("ProfileData.php");
require_once("User.php");
require_once("config.php");
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

    $backgroundImageSection = $this->createBackgroundImageSection();
    $headerSection = $this->createHeaderSection();
    $tabsSection = $this->createTabsSection();
    $contentSection = $this->createContentSection();
    $newBackgroundImage = $this->updateBackgroundImage($this->userLoggedInObj->getUsername());

    return "<div class='profileContainer'>
                $backgroundImageSection
                $headerSection
                $tabsSection
                $contentSection
                $newBackgroundImage
            </div>";
    }

    public function createBackgroundImageSection()
    {
		$profileDataUsername = $this->profileData->getProfileUsername();
		$userLoggedInObjUsername = $this->userLoggedInObj->getUsername();
		$backgroundImageObj = $this->profileData->getBackgroundImage();
		$editButtonDisplay = $profileDataUsername == $userLoggedInObjUsername ? "style='display:block;'" : "style='display:none;'";
        $name = $this->profileData->getProfileUserFullName();
        return "<div class='backgroundImageContainer'>
					<img src='$backgroundImageObj' class='backgroundImage'>
					<div class='editBackgroundImageButton' <?php $editButtonDisplay ;?>
						
                        <form action='' method='POST' enctype='multipart/form-data'>
                            <input type='file' onchange='this.form.submit()' name='backgroundImage' >
                        </form>
                    </div>
                </div>";
    }

    public function setBackgroundImage()
    {
		$target_dir = "uploads/backgroundImages/";
		$backgroundImagePath = $target_dir . basename($_FILES["backgroundImage"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($backgroundImagePath,PATHINFO_EXTENSION));
		$check = getimagesize($_FILES["backgroundImage"]["tmp_name"]);
		if($check != true) {
			echo "File is not an image.";
			$uploadOk = 0;
		}
		if ($_FILES["backgroundImage"]["size"] > 5000000000) {
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
		if (!move_uploaded_file($_FILES["backgroundImage"]["tmp_name"], $backgroundImagePath))
		{
			echo "Sorry, there was an error uploading your file.";
		}
		header("Refresh:0");
		return $backgroundImagePath;
    }

    public function updateBackgroundImage($username)
    {
        if(isset($_FILES['backgroundImage']))
        {
            $newBackgroundImagePath = $this->setBackgroundImage();
            $query = $this->con->prepare("

            UPDATE users SET backgroundImage=:backgroundImage WHERE username=:username
            
            ");
            
            $query->bindParam(":backgroundImage", $newBackgroundImagePath); 
            $query->bindParam(":username", $username); 
            
            
            return $query->execute();
        }
    }
    
    public function createHeaderSection()
    {
        $profileImage = $this->profileData->getAvatar();
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
            return Button::createSubscriberButton($this->con, $this->profileData->getProfileUserObj(), $this->userLoggedInObj);
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