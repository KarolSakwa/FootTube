<?php 
require_once("Button.php");

class CommentControls
{
	private $con, $comment, $userLoggedInObj;
	
	public function __construct($con, $comment, $userLoggedInObj)
	{
		$this->con = $con;
		$this->comment = $comment;
		$this->userLoggedInObj = $userLoggedInObj;
	}
	
	public function create()
	{
        $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $videoId = $this->comment->getVideoId();
        $replyButton = (!strpos($actual_link, 'profile.php')) ? $this->createReplyButton() : "";
        $likesCount = (!strpos($actual_link, 'profile.php')) ? $this->createLikesCount() : "";
		$likeButton = (!strpos($actual_link, 'profile.php')) ? $this->createLikeButton() : "";
        $dislikeButton = (!strpos($actual_link, 'profile.php')) ? $this->createDislikeButton() : "";
        $replySection = (!strpos($actual_link, 'profile.php')) ? $this->createReplySection() : "";
		
		return "
		
        <div class='controls'>
            $replyButton
            $likesCount
			$likeButton
            $dislikeButton
            
		</div>
		$replySection
		";
    }
    
    private function createReplyButton()
    {
        $text = "REPLY";
        $action = "toggleReply(this)";

        return Button::createButton($text, null, null, $action);
    }

    private function createLikesCount()
    {
        $text = $this->comment->getLikes();

        if($text == 0)
        {
            $text = "";
        }
        return "<span class='likesCount'>$text</span>";
    }

    private function createReplySection()
    {
        $postedBy = $this->userLoggedInObj->getUsername();
        $videoId = $this->comment->getVideoId();
        $commentId = $this->comment->getId();

        $profileButton = Button::createUserProfileButton($this->con, $postedBy);
        $cancelButtonAction = "toggleReply(this)";
        $cancelButton = Button::createButton("Cancel", "cancelComment", null, $cancelButtonAction);
        $postButtonAction = "postComment(this, \"$postedBy\", $videoId, $commentId, \"repliesSection\")";
        $postButton = Button::createButton("Reply", "postComment", null, $postButtonAction);
        

        return 
        "<div class='commentForm hidden'>
            $profileButton
            <textarea class='commentBodyClass' placeholder='Add a public comment'></textarea>
            $cancelButton
            $postButton
        </div>";
        
    }
	private function createLikeButton()
	{
		$text = $this->comment->getLikes();
		$class = "likeButton";
		$imageSrc = "static/img/icons/thumb-up.png";
        $videoId = $this->comment->getVideoId();
        $commentId = $this->comment->getId();
		$action = "likeComment($commentId, this, $videoId)";
		
		if($this->comment->wasLikedBy())
		{
			$imageSrc = "static/img/icons/thumb-up-active.png";
		}
		
		return Button::createButton("", $class, $imageSrc, $action);
	}
	private function createDislikeButton()
	{
        $commentId = $this->comment->getId();
		$class = "dislikeButton";
		$imageSrc = "static/img/icons/thumb-down.png";
		$videoId = $this->comment->getVideoId();
		$action = "dislikeComment($commentId, this, $videoId)";
		
		if($this->comment->wasDislikedBy())
		{
			$imageSrc = "static/img/icons/thumb-down-active.png";
		}
		
		return Button::createButton("", $class, $imageSrc, $action);
	}
	
	
}

?>