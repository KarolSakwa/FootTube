<?php
require_once("Comment.php");
require_once("Video.php");


class ProfileData {
    
    private $con, $profileUserObj, $sqlData;

    public function __construct($con, $profileUsername) 
    {
        $this->con = $con;
        $this->profileUserObj = new User($con, $profileUsername);
    }

    public function getProfileUserObj()
    {
        return $this->profileUserObj;
    }

    public function getProfileUsername() 
    {
        return $this->profileUserObj->getUsername();
    }

    public function userExists()
    {
        $query = $this->con->prepare("
        SELECT * FROM users WHERE username=:username
        ");
        $query->bindParam(":username", $profileUsername);
        $profileUsername = $this->getProfileUsername();
        $query->execute();

        return $query->rowCount() != 0;
    }

    public function getDefaultBackgroundImage()
    {
        return "static/img/backgroundImages/default-cover-photo.jpg";
    }

    public function getBackgroundImage()
	{
		return $this->profileUserObj->getBackgroundImage();
	}

    public function getProfileUserFullName()
    {
        return $this->profileUserObj->getName();
    }
	
    public function getAvatar()
    {
        return $this->profileUserObj->getAvatar();
    }
	
	public function getSubscriberCount()
    {
        return $this->profileUserObj->getSubscriberCount();
    }
	
	public function getUsersVideos()
	{
		$query = $this->con->prepare("
		SELECT * FROM videos WHERE uploadedBy=:uploadedBy ORDER BY uploadDate DESC
		");
		$query->bindParam(":uploadedBy", $username);
		$username = $this->getProfileUsername();
		$query->execute();
		
		$videos = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC))
		{
			$videos[] = new Video($this->con, $row, $this->profileUserObj->getUsername());
		}
		return $videos;
	}
	
	public function getAllUserDetails()
	{
		return array(
			"Name" => $this->getProfileUserFullName(),
			"Username" => $this->getProfileUsername(),
			"Subscribers" => $this->getSubscriberCount(),
            "Total views" => $this->getTotalViews(),
            "Total posted comments" => $this->getTotalPostedComments(),
            "Registration date" => $this->getRegistrationDate(),
		);
	}
	
	private function getTotalViews()
	{
        $query = $this->con->prepare("SELECT sum(views) FROM videos WHERE uploadedBy=:uploadedBy");
        $query->bindParam(":uploadedBy", $username);
        $username = $this->getProfileUsername();
        $query->execute();
        $totalViews = $query->fetchColumn();

        return $totalViews != 0 ? $totalViews : 0;
    }
    private function getTotalPostedComments()
	{
        $query = $this->con->prepare("SELECT * FROM comments WHERE postedBy=:postedBy");
        $query->bindParam(":postedBy", $username);
        $username = $this->getProfileUsername();
        $query->execute();

        return $query->rowCount();
    }
	
	private function getRegistrationDate()
	{
        $date = $this->profileUserObj->getRegistrationDate();
        
        return date("F jS, Y", strtotime($date));
    }
    public function getUserComments()
    {
        $query = $this->con->prepare("
		SELECT * FROM comments WHERE postedBy=:postedBy ORDER BY datePosted ASC
		");

        $query->bindParam(":postedBy", $username);
        $username = $this->getProfileUsername();
        $query->execute();	

        $comments = "";
        
		while ($row = $query->fetch(PDO::FETCH_ASSOC))
		{
            $comment = new Comment($this->con, $row, $this->profileUserObj, null);
            $comments .= $comment->displayOnUsersProfile();
            
        }

		return $comments;
    }
}
?>