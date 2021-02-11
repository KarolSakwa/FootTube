<?php include_once("includes/header.php");
include_once("classes/VideoUploadData.php");
include_once("classes/VideoProcessor.php");

if(!isset($_POST['uploadButton']))
{
    echo "no form data has been sent";
}

$videoUploadData = new VideoUploadData(
    $_FILES['fileInput'],
    $_POST['titleInput'],
    $_POST['descriptionInput'],
    $_POST['privacyInput'],
    $_POST['categoryInput'],
    $userLoggedInObj->getUsername());

$videoProcessor = new VideoProcessor($con);

$wasSuccessful = $videoProcessor->upload($videoUploadData);

if($wasSuccessful)
{
    echo "Video uploaded!";
}

?>
