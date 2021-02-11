<?php require_once("config.php");

require_once("classes/FormSanitizer.php");
require_once("classes/Constants.php");
require_once("classes/Account.php");

$account = new Account($con);

if(isset($_POST['submitButton']))
{
    $username = FormSanitizer::sanitizeFormUserName($_POST['username']);
    $password = FormSanitizer::sanitizeFormPassword($_POST['password']);
    

    $wasSuccessful = $account->login($username, $password);
    
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


?>

<!DOCTYPE html>
<html>
    <head>
        <title>FootTube</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="static/css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="logInContainer">
            <div class="column">
                <div class="header">
                    <img title="logo" alt="Logo" src="static/img/icons/FootTubeLogo.png"/>
                    <h3>Log in!</h3>
                    <span>to start using FootTube's full funcionality</span>
                </div>

                <div class="loginForm">
                    <form method="POST" action="logIn.php" >
                        <?php echo $account->getError(Constants::$invalidLogin); ?>
                        <input type="text" name="username" placeholder="Username" value="<?php getUserDetails("username");?>" autocomplete="off" required />
                        <input type="password" name="password" placeholder="Password" value="<?php getUserDetails("password");?>" required/>
                        <input type="submit" name="submitButton" value="SUBMIT"/>

                    </form> 
                </div>

                <a class="logInMessage" href="register.php">
                    Do you need a new account? Register here!
                </a>
            </div>
        </div>
    </body>

    </html>