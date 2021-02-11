<?php 

class CommentSection
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
        $numComments = $this->video->getNumberOfComments();
        $postedBy = $this->userLoggedInObj->getUsername();
        $videoId = $this->video->getId();

        $profileButton = Button::createLoggedInUserProfileButton($this->con, $postedBy);
        $commentAction = "postComment(this, \"$postedBy\", $videoId, null, \"comments\")";
        $commentButton = Button::createButton("COMMENT", "postComment", null, $commentAction);

        $comments = $this->video->getComments();
        $commentItems = "";
        foreach($comments as $comment)
        {
            $commentItems .= $comment->create();
        }

        return "<div class='commentSection'>
            <div class='header'>
                <span class='commentCount'>
                    $numComments Comments
                    <div class='commentForm'>
                        $profileButton
                        <textarea class='commentBodyClass' placeholder='Add a public comment'></textarea>
                        $commentButton

                    </div>
                </span>
            </div>
            <div class='comments'>
                $commentItems
            </div>
        </div>";
    }
}
?>