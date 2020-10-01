<?php 

class ButtonProvider
{
	public static $signInFunction = "notSignIn()";
	
	public static function createLink($link)
	{
		return User::isLoggedIn() ? $link : ButtonProvider::$signInFunction;
	}
	
	public static function createButton($text, $class, $imageSrc, $action)
	{
		// if the image is not null
		
		$image = ($imageSrc == null) ? "" : "<img src='$imageSrc'>"; 
		
		$action = ButtonProvider::createLink($action);
		
		return "<button class='$class' onclick='$action'>
				$image
			<span class='text'>
				$text
			</span>
		
		</button>";
	}
	
	public static function createUserProfileButton($con, $username)
	{
		$userObj = new User($con, $username);
		$profilePic = $userObj->getProfilePic();
		$link = "profile.php?username=$username";
		
		return "
		<a href='$link'>
			<img src='$profilePic' class='profilePicture'>
		</a>
		";
	}

	public static function createUserProfileButtonIfNotLoggedIn($con, $username)
	{
		$userObj = new User($con, $username);
		$defaultProfilePic = "uploads\avatar\default.png";
		$profilePic = User::isLoggedIn() ? $userObj->getProfilePic() : $defaultProfilePic;
		$link = "profile.php?username=$username";
		
		return "
		<a href='$link'>
			<img src='$profilePic' class='profilePicture'>
		</a>
		";
	}
	
	public static function createEditVideoButton($videoId)
	{
		$href = "editVideo.php?videoId=$videoId";
		
		$button = ButtonProvider::createHyperLinkButton("EDIT VIDEO", "edit button", null, $href);
		
		return "
		<div class='editVideoButtonContainer'>
			$button
		</div>
		";
	}
	
		public static function createHyperLinkButton($text, $class, $imageSrc, $href)
	{
		// if the image is not null
		
		$image = ($imageSrc == null) ? "" : "<img src='$imageSrc'>"; 
		
		
		
		return "<a href='$href'>
		<button class='$class'>
				$image
			<span class='text'>
				$text
			</span>
		
		</button>
		</a>";
	}

	public static function createSubscriberButton($con, $userToObj, $userLoggedInObj)
	{
		$userTo = $userToObj->getUsername();
		$userLoggedIn = $userLoggedInObj->getUsername();
		$isSubscribedTo = $userLoggedInObj->isSubscribedTo($userToObj->getUsername());
		$buttonText = $isSubscribedTo ? "SUBSCRIBED" : "SUBSCRIBE";
		$buttonText .= " " . $userToObj->getSubscriberCount();

		$buttonClass = $isSubscribedTo ? "unsubscribe button" : "subscribe button";
		$action = "subscribe(\"$userTo\", \"$userLoggedIn\", this)"; 

		$button = ButtonProvider::createButton($buttonText, $buttonClass, null, $action);

		return "<div class='subscribeButtonContainer'
			<button>$button	</button>
			
		</div>
		";
	}
	public static function createUserProfileNavigationButton($con, $username)
	{
		if(User::isLoggedIn())
		{
			return ButtonProvider::createUserProfileButton($con, $username);
		}
		else
		{
			return "<a href='signIn.php'>
						<span class='signInLink'>SIGN IN</span>
					</a>";
		}
	}
}

?>