<?php
class SettingsFormProvider {


	// creating a function to create the form
	
	public function createUserDetailsForm($firstName, $lastName, $email, $avatar){
		$firstNameInput = $this->createFirstNameInput($firstName);
		$lastNameInput = $this->createLastNameInput($lastName);
		$emailInput = $this->createEmailInput($email);
		$avatarInput = $this->createAvatarInput($avatar);
		$saveButton = $this->createSaveUserDetailsButton();
		return "
		<form action='settings.php' method='POST' enctype='multipart/form-data'>
			<span class='title'>User details</span>
			$firstNameInput
			$lastNameInput
			$emailInput
			$avatarInput
			$saveButton

			
		</form>
		";
	}
	
	public function createPasswordForm()
	{
		$oldPasswordInput = $this->createPasswordInput("oldPassword", "Old password");
		$newPassword1Input = $this->createPasswordInput("newPassword", "New password");
		$newPassword2Input = $this->createPasswordInput("newPassword2", "Confirm new password");

		$saveButton = $this->createSavePasswordButton();
		return "
		<form action='settings.php' method='POST' enctype='multipart/form-data'>
			<span class='title'>Update password</span>
			$oldPasswordInput
			$newPassword1Input
			$newPassword2Input
			$saveButton

			
		</form>
		";
	}
		
	private function createFirstNameInput($value)
	{
		if($value == null) $value = "";
		return "
		<div class='form-group'> First name
			<input type='text' name='firstName' class='form-control' placeholder='First Name' value='$value' required>
		</div>
		";
	}
	
	private function createLastNameInput($value){
		if($value == null) $value = "";
		return "
		<div class='form-group'> Last name
			<input type='text' name='lastName' class='form-control' placeholder='Last Name' value='$value' required>
		</div>
		";
	}
	
	private function createEmailInput($value){
		if($value == null) $value = "";
		return "
		<div class='form-group'> Email
			<input type='email' name='email' class='form-control' placeholder='Email' value='$value' required>
		</div>
		";
	}

	private function createAvatarInput($value){
		if($value == null) $value = "";
		return '  
		<div class="form-group"> Profile picture
			<input type="file" name="avatar" class="form-control-file" id="avatar" >
		</div>
		';
	}
	
	private function createSaveUserDetailsButton()
	{
		$html = "<button type='submit' name='saveDetailsButton' class='btn btn-primary'>Save</button>";
	
		return $html;
	}
	
	private function createSavePasswordButton()
	{
		$html = "<button type='submit' name='savePasswordButton' class='btn btn-primary'>Save</button>";
	
		return $html;
	}
	
	private function createPasswordInput($name, $placeholder){
		
		return "
		<div class='form-group'>
			<input type='password' name='$name' class='form-control' placeholder='$placeholder' required>
		</div>
		";
	}
}
?>