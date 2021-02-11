<?php 

class Button
{
	public static $logInFunction = "notLoggedIn()";
	
	public static function createLink($link)
	{
		return User::isLoggedIn() ? $link : Button::$logInFunction;
	}
	
	public static function createButton($text, $class, $imageSrc, $action)
	{
		// if the image is not null
		
		$image = ($imageSrc == null) ? "" : "<img src='$imageSrc'>"; 
		
		$action = Button::createLink($action);
		
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
		$avatar = $userObj->getAvatar();
		$link = "profile.php?username=$username";
		
		return "
		<a href='$link'>
			<img src='$avatar' class='avatar'>
		</a>
		";
	}

	public static function createLoggedInUserProfileButton($con, $username)
	{
		$userObj = new User($con, $username);
		$defaultAvatar = "static\img\avatars\default.png";
		$avatar = User::isLoggedIn() ? $userObj->getAvatar() : $defaultAvatar;
		$link = "profile.php?username=$username";
		
		return "
		<a href='$link'>
			<img src='$avatar' class='avatar'>
		</a>
		";
	}
	
	public static function createEditVideoButton($videoId)
	{
		$href = "editVideo.php?videoId=$videoId";
		
		$button = Button::createHyperLinkButton("EDIT VIDEO", "edit button", null, $href);
		
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

		$button = Button::createButton($buttonText, $buttonClass, null, $action);

		return "<div class='subscribeButtonContainer'
			<button>$button	</button>
			
		</div>
		";
	}
	public static function createUserProfileNavigationButton($con, $username)
	{
		if(User::isLoggedIn())
		{
			return Button::createUserProfileButton($con, $username);
		}
		else
		{
			return "<a href='logIn.php'>
						<span class='logInLink'>LOG IN</span>
					</a>";
		}
	}
}

?>