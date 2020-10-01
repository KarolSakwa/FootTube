<?php

require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/Account.php");



//processing the signup form

$account = new Account($con);


if(isset($_POST['submitButton']))
{
    $firstname = FormSanitizer::sanitizeFormString($_POST['firstName']);
    $lastname = FormSanitizer::sanitizeFormString($_POST['lastName']);
    $username = FormSanitizer::sanitizeFormUserName($_POST['username']);
    $email = FormSanitizer::sanitizeFormEmail($_POST['email']);
    $confirmEmail = FormSanitizer::sanitizeFormEmail($_POST['email2']);
    $password = FormSanitizer::sanitizeFormPassword($_POST['password']);
    $confirmPassword = FormSanitizer::sanitizeFormPassword($_POST['password2']);
    
    echo $firstname;
    echo $lastname;
    echo $username;
    echo $email;
    echo $confirmEmail;
    echo $password;
    echo $confirmPassword;

    $wasSuccessful = $account->register($firstname, $lastname, $username, $email, $confirmEmail, $password, $confirmPassword);
    
    if ($wasSuccessful)
    {
		$_SESSION['userLoggedIn'] = $username;
		header("Location: index.php");
    }
}

	function getUserDetails($name)
	{
		if(isset($_POST[$name]))
		{
			echo $_POST[$name];
		}
    }
    


if(isset($_POST['submitButton']))
{
    $target_dir = "uploads/avatar/";
    $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["avatar"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    }
    else
    {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    if ($_FILES["avatar"]["size"] > 500000) {
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
    if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) 
    {
        echo "The file ". basename( $_FILES["avatar"]["name"]). " has been uploaded.";
        $query = $con->prepare("
        INSERT INTO users(avatar) VALUES (:avatar) 
        ");
        $query->bindParam(":avatar", $target_file);
        $query->execute();
    }
    else
    {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>FootTube</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="signInContainer">
            <div class="column">
                <div class="header">
                    <img title="logo" alt="Logo" src="assets/images/icons/FootTubeLogo.png"/>
                    <h3>Sign up!</h3>
                    <span>to join FootTube community   </span>
                </div>

                <div class="loginForm">
                    <form action="signUp.php" method="POST" enctype="multipart/form-data">
					
						<?php echo $account->getError(Constants::$firstnameMinimumCharacters); ?>
                        <input type="text" name="firstName" placeholder="First Name" value="<?php getUserDetails('firstName'); ?>" autocomplete="off" required>
						<?php echo $account->getError(Constants::$lastnameMinimumCharacters); ?>
                        <input type="text" name="lastName" placeholder="Last Name" value="<?php getUserDetails('lastName'); ?>" autocomplete="off" required>
                        <?php echo $account->getError(Constants::$usernameMinimumCharacters); ?>
                        <?php echo $account->getError(Constants::$usernameExists); ?>
                        <input type="text" name="username" placeholder="Username" value="<?php getUserDetails('username'); ?>" autocomplete="off" required>
                        <?php echo $account->getError(Constants::$emailMatching); ?>
                        <?php echo $account->getError(Constants::$invalidEmail); ?>
                        <?php echo $account->getError(Constants::$emailExists); ?>
                        <input type="email" name="email" placeholder="Email" value="<?php getUserDetails('email'); ?>" autocomplete="off" required>
                        <input type="email" name="email2" placeholder="Confirm email" value="<?php getUserDetails('email2'); ?>" autocomplete="off" required>
                        <?php echo $account->getError(Constants::$passwordMatching); ?>
                        <?php echo $account->getError(Constants::$invalidPassword); ?>
                        <?php echo $account->getError(Constants::$passwordLength); ?>
                        <input type="password" name="password" placeholder="Password" value="<?php getUserDetails('password'); ?>" autocomplete="off" required>
                        <input type="password" name="password2" placeholder="Confirm password" value="<?php getUserDetails('password2'); ?>" autocomplete="off" required>
                        <div class="avatar"> <label> Select your profile picture: </label> <input type="file" name="avatar" accept="image/*" required /> </div>

                        <input type="submit" name="submitButton" value="SUBMIT">

                    </form>
                </div>

                <a class="signInMessage" href="signIn.php">
                    Already have an account? Sign in here!
                </a>
            </div>
        </div>
    </body>

    </html>