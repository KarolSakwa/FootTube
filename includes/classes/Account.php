<?php

class Account
{
	private $con;
	private $errorArray = array();
	
	
	public function __construct($con)
	{
		$this->con = $con;
	}

	public function login($username, $password)
	{
		// hash the password 
		$password = hash("sha512", $password);
		$query = $this->con->prepare("
		
		SELECT * from users WHERE username=:username AND password=:password
		
		");

		$query->bindParam(":username", $username);
		$query->bindParam(":password", $password);

		$query->execute();

		if($query->rowCount() == 1)
		{
			return true;
		}
		else
		{
			array_push($this->errorArray, Constants::$invalidLogin);
			return false;
		}
	}
	
	public function register($firstname, $lastname, $username, $email, $confirmEmail, $password, $confirmPassword)
	{
		$this->validateFirstName($firstname);
		$this->validateLastName($lastname);
		$this->validateUserName($username);
		$this->validateEmails($email, $confirmEmail);
		$this->validatePasswords($password, $confirmPassword);

		if(empty($this->errorArray))
		{
			return $this->insertUserDetails($firstname, $lastname, $username, $email, $password);
		}
		else
		{
			return false;
		}
	}
	
	public function updateDetails($fn, $ln, $em, $un)
	{
		$this->validateFirstName($fn);
		$this->validateLastName($ln);
		$this->validateNewEmail($em, $un);
		$avatarPath = $this->setAvatar();

		if(empty($this->errorArray))
		{
			$query = $this->con->prepare("
			UPDATE users SET firstname=:fn, lastname=:ln, email=:em, avatar=:av WHERE username=:un
			");
			
			$query->bindParam(":fn", $fn);
			$query->bindParam(":ln", $ln);
			$query->bindParam(":em", $em);
			$query->bindParam(":av", $avatarPath);
			$query->bindParam(":un", $un);
			
			return $query->execute();
		}
		else
		{
			return false;
		}
	}
	public function updatePassword($oldPw, $pw, $pw2, $un)
	{
		$this->validateOldPassword($oldPw, $un);
		$this->validatePasswords($pw, $pw2);
		
		if(empty($this->errorArray))
		{
			$query = $this->con->prepare("
			UPDATE users SET password=:pw WHERE username=:un
			");
			$pw = hash("sha512", $pw);
			$query->bindParam(":pw", $pw);
			$query->bindParam(":un", $un);
			
			return $query->execute();
		}
		else
		{
			return false;
		}
	}
	
	private function validateOldPassword($oldPw, $un)
	{
		$password = hash("sha512", $oldPw);
		$query = $this->con->prepare("
		
		SELECT * from users WHERE username=:username AND password=:password
		
		");

		$query->bindParam(":username", $username);
		$query->bindParam(":password", $password);

		$query->execute();

		if($query->rowCount() == 0)
		{
			array_push($this->errorArray, Constants::$passwordIncorrect);
		}
	}

	private function insertUserDetails($firstname, $lastname, $username, $email, $password)
	{
		//converting the pass into hash
		$password = hash("sha512", $password);
		$profilePic = "assets/images/profilePictures/default.png";
		$avatarPath = $this->setAvatar();
		$coverPhotoDefaultPath = "assets/images/coverPhotos/default-cover-photo.jpg";
		$query = $this->con->prepare("
		
		INSERT INTO users (firstName, lastName, username, email, password, profilePic, avatar, coverPhoto)
		
		VALUES(:firstName, :lastName, :username, :email, :password, :profilePic, :avatar, :coverPhoto)
		
		");
		
		$query->bindParam(":firstName", $firstname); 
		$query->bindParam(":lastName", $lastname); 
		$query->bindParam(":username", $username); 
		$query->bindParam(":email", $email); 
		$query->bindParam(":password", $password); 
		$query->bindParam(":profilePic", $profilePic); 
		$query->bindParam(":avatar", $avatarPath); 
		$query->bindParam(":coverPhoto", $coverPhotoDefaultPath); 
		
		return $query->execute();
		
	}
	
	private function setAvatar()
	{
		$target_dir = "uploads/avatar/";
		$avatarPath = $target_dir . basename($_FILES["avatar"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($avatarPath,PATHINFO_EXTENSION));
		$check = getimagesize($_FILES["avatar"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		}
		else
		{
			echo "File is not an image.";
			$uploadOk = 0;
		}
		if ($_FILES["avatar"]["size"] > 5000000000) {
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
		if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $avatarPath)) 
		{
			echo "Sorry, there was an error uploading your file.";
		}
		return $avatarPath;
	}

	private function validateFirstName($firstname)
	{
		if(strlen($firstname) > 25 || strlen($firstname) < 2)
		{
			array_push($this->errorArray, Constants::$firstnameMinimumCharacters);
		}
	}
	
	private function validateLastName($lastname)
	{
		if(strlen($lastname) > 25 || strlen($lastname) < 2)
		{
			array_push($this->errorArray, Constants::$lastnameMinimumCharacters);
		}
	}
	private function validateUserName($username)
	{
		if(strlen($username) > 25 || strlen($username) < 5)
		{
			array_push($this->errorArray, Constants::$usernameMinimumCharacters);
			return;
		}
		$query = $this->con->prepare("
			SELECT username from users WHERE username=:username
			");

			$query->bindParam(":username", $username);
			
			$query->execute();

			if($query->rowCount() != 0)
			{
				// checking if username exists 
				array_push($this->errorArray, Constants::usernameExists);
			}
	}

	private function validateEmails($email, $confirmEmail)
	{
		if($email != $confirmEmail)
		{
			array_push($this->errorArray, Constants::$emailMatching);
			return;
		}
		//check if the email is valid or not

		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			array_push($this->errorArray, Constants::$invalidEmail);
			return;
		}
		$query = $this->con->prepare("
			SELECT email from users WHERE email=:email
			");

			$query->bindParam(":email", $email);
			
			$query->execute();

			if($query->rowCount() != 0)
			{
				// checking if username exists 
				array_push($this->errorArray, Constants::emailExists);
			}
	}
	
		private function validateNewEmail($em, $un)
	{
		if(!filter_var($em, FILTER_VALIDATE_EMAIL))
		{
			array_push($this->errorArray, Constants::$invalidEmail);
			return;
		}
		$query = $this->con->prepare("
			SELECT email from users WHERE email=:em AND username != :un
			");

			$query->bindParam(":em", $em);
			$query->bindParam(":un", $un);
			
			$query->execute();

			if($query->rowCount() != 0)
			{
				// checking if username exists 
				array_push($this->errorArray, Constants::emailExists);
			}
	}

	private function validatePasswords($password, $confirmPassword)
	{
		if($password != $confirmPassword)
		{
			array_push($this->errorArray, Constants::$passwordMatching);
			return;
		}
		
		if(preg_match("/[^A-Za-z0-9]/", $password))
		{
			array_push($this->errorArray, Constants::$invalidPassword);
			return;
		}

		if(strlen($password) > 25 || strlen($password) < 2)
		{
			array_push($this->errorArray, Constants::$passwordLength);
			return;
		}

		
	}
	
	public function getError($error)
	{
		if(in_array($error, $this->errorArray))
		{
			return "<span class='errorMessage'>$error</span>";
		}
	}
	
	public function getFirstError()
	{
		if(!empty($this->errorArray))
		{
			return $this->errorArray[0];
		}
		else
		{
				return "";
		}
	}
	
}




?>