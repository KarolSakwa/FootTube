<?php
class VideoDetailsForm {

	private $con;

	public function __construct($con)
	{
		$this->con = $con;
	}
	
	public function createUploadForm(){
		$fileInput = $this->createFileInput();
		$titleInput = $this->createTitleInput(null);
		$descriptionInput = $this->createDescriptionInput(null);
		$privacyInput = $this->createPrivacyInput(null);
		$categoryInput = $this->createCategoryInput(null);
		$uploadButton = $this->createUploadButton();
		return "
		<form action='dataProcessing.php' method='POST' enctype='multipart/form-data'>
			$fileInput
			$titleInput
			$descriptionInput
			$privacyInput
			$categoryInput
			$uploadButton
		</form>
		";
	}
	
	public function createEditDetailsForm($video)
	{
		$titleInput = $this->createTitleInput($video->getTitle());
		$descriptionInput = $this->createDescriptionInput($video->getDescription());
		$privacyInput = $this->createPrivacyInput($video->getPrivacy());
		$categoryInput = $this->createCategoryInput($video->getCategory());
		$saveButton = $this->createSaveButton();
		return "
		<form method='POST'>
		
			$titleInput
			$descriptionInput
			$privacyInput
			$categoryInput
			$saveButton

			
		</form>
		";
	}
	
	private function createFileInput()
	{
		return '  
		<div class="form-group">
			<input type="file" name="fileInput" class="form-control-file" id="exampleFormControlFile1" required>
		</div>
		';
	}
	
	private function createTitleInput($value)
	{
		if ($value == null) $value = "";
		return "
		<div class='form-group'>
			<input type='text' name='titleInput' class='form-control' placeholder='Title' required value='$value'>
		</div>
		";
	}
	
	private function createDescriptionInput($description)
	{
		if ($description == null) $description = "";
		
		return "
		<div class='form-group'>
			<textarea class='form-control' name='descriptionInput' placeholder='Description' rows='3'>$description</textarea>
		</div>
		";
	}
	
	private function createPrivacyInput($value)
	{
		if ($value == null) $value = "";
		
		$privateSelected = ($value == 0) ? "selected='selected'" : "";
		$publicSelected = ($value == 1) ? "selected='selected'" : "";
		return '
		<div class="form-group">
			<select class="form-control" name="privacyInput" id="exampleFormControlSelect1">
				<option value="0" $privateSelected>Public</option>
				<option value="1" $publicSelected>Private</option>
			</select>
		</div>
		';
	}

	private function createCategoryInput($value)
	{
		if ($value == null) $value = "";
		$query = $this->con->prepare("SELECT * from categories");

		$query->execute();

		$html = '
		<div class="form-group">
		<select class="form-control" name="categoryInput">
		';

		while ($row = $query->fetch(PDO::FETCH_ASSOC))
		{
			$name = $row["name"];
			$id = $row["id"];
			$selected = ($id == $value) ? "selected='selected'" : "";
			$html.= "<option value='$id'>$name</option>";
		}

		$html.='</select>
		</div>
		';

		return $html;
	}

	private function createUploadButton()
	{
		$html = "<button name='uploadButton' class='btn btn-primary'>Upload</button>";
	
		return $html;
	}
	private function createSaveButton()
	{
		$html = "<button name='saveButton' class='btn btn-primary'>Save</button>";
	
		return $html;
	}
}
?>