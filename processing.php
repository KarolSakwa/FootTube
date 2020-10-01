<?php include_once('includes/header.php');
include_once('includes/classes/VideoUploadData.php');
include_once('includes/classes/VideoProcessor.php');

//check for submission of form

if(!isset($_POST['uploadButton']))
{
    echo "no form data has been sent";
}

// make the file upload data

$videoUploadData = new VideoUploadData(
    $_FILES['fileInput'],
    $_POST['titleInput'],
    $_POST['descriptionInput'],
    $_POST['privacyInput'],
    $_POST['categoryInput'],
    $userLoggedInObj->getUsername());

$videoProcessor = new VideoProcessor($con);

$wasSuccessful = $videoProcessor->upload($videoUploadData);



// processing the video converting to mp4

// check for the successfull upload of the video

    if($wasSuccessful)
    {
        echo "Video uploaded!";
    }

?>
