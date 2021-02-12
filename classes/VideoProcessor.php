<?php

class VideoProcessor
{
    private $con;

    private $sizeLimit = 250000000;
    private $ffmpegPath;
    private $ffprobePath;
    private $allowedTypes = array('mp4', 'flv', 'avi', 'mov', 'pdf');

    public function __construct($con)
    {
        $this->con = $con;
        $this->ffmpegPath = "ffmpeg";
        $this->ffprobePath = "ffprobe";
    }

    public function upload($videoUploadData)
    {
        $targetDir = "uploads/videos/";
        $videoData = $videoUploadData->getVideoArray();
        $tempFilePath = $targetDir . uniqid() . basename($videoData["name"]);
        $tempFilePath = str_replace(" ","_", $tempFilePath);
        $isValidData = $this->processData($videoData, $tempFilePath);

        if (!$isValidData)
        {
            return false;
        }
        if(move_uploaded_file($videoData["tmp_name"], $tempFilePath))
        {
            $finalFilePath = $targetDir . uniqid() . ".mp4";

            if(!$this->insertVideoData($videoUploadData, $finalFilePath))
            {
                echo "Insert query failed!";
                return false;
            }
            if(!$this->convertVideoToMp4($tempFilePath, $finalFilePath))
            {
                echo "Command failed!";
                return false;
            }
            if(!$this->deleteFile($tempFilePath))
            {
                echo "Couldn't delete the original file!";
                return false;
            }

            if(!$this->generateThumbnails($finalFilePath))
            {
                echo "Upload failed!";
                return false; 
            }

            return true;

        }

    }

    private function processData($videoData, $tempFilePath)
    {
        $videoType = pathinfo($tempFilePath, PATHINFO_EXTENSION);

        if(!$this->isValidSize($videoData))
        {
            echo "The file is too large to upload!";
            return false;
        }
        else if (!$this->isValidType($videoType))
        {
            echo "Invalid file type!";
            return false;
        }
        else if ($this->hasError($videoData))
        {
            echo "An error occured!";
            return false;
        }
        return true;
    }

    private function isValidSize($videoData)
    {
        return $videoData["size"] <= $this->sizeLimit;
    }

    private function isValidType($videoType)
    {
        $videoType = strtolower($videoType);
        return in_array($videoType, $this->allowedTypes);
    }
    private function hasError($videoData)
    {
        return $videoData["error"] !=0;
    }

    private function insertVideoData($videoUploadData, $finalFilePath)
    {
        $title = $videoUploadData->getTitle();
        $uploadedBy = $videoUploadData->getUploadedBy();
        $description = $videoUploadData->getDescription();
        $privacy = $videoUploadData->getPrivacy();
        $category = $videoUploadData->getCategory();

        $query = $this->con->prepare(
            "INSERT INTO videos (title, uploadedBy, description, privacy, category, filePath)
            VALUES (:title, :uploadedBy, :description, :privacy, :category, :filePath)");

            $query->bindParam(":title", $title);
            $query->bindParam(":uploadedBy", $uploadedBy);
            $query->bindParam(":description", $description);
            $query->bindParam(":privacy", $privacy);
            $query->bindParam(":category", $category);
            $query->bindParam(":filePath", $finalFilePath);

            return $query->execute(); // true or false
        
    }

    private function convertVideoToMp4($tempFilePath, $finalFilePath)
    {
        $cmd = "$this->ffmpegPath -i $tempFilePath $finalFilePath 2>&1";

        $outputLog = array();

        exec($cmd, $outputLog, $returnCode);

        if($returnCode != 0)
        {
            foreach($outputLog as $line)
            {
                echo $line . "<br>";
                return false;
            }
        }

        return true;

    }

    private function deleteFile($filePath)
    {
        if(!unlink($filePath))
        {
            echo "Couldn't delete the file";
            return false;
        }

        return true;
    }
    public function generateThumbnails($filePath)
    {

        $thumbnailSize = "210x118";

        $numThumbnails = 3;

        $pathToThumbnail = "uploads/videos/thumbnails";

        $duration = $this->getVideoDuration($filePath);

        $videoId = $this->con->lastInsertId();

        $this->updateDuration($duration, $videoId);

        for ($num = 1; $num<=3; $num++)
        {
            $imageName = uniqid() . ".jpg";
            $interval = ($duration * 0.8) / $numThumbnails * $num;

            $fullThumbnailPath = "$pathToThumbnail/$videoId-$imageName";

            $cmd = "$this->ffmpegPath -i $filePath -ss $interval -s $thumbnailSize -vframes 1 $fullThumbnailPath";

            $outputLog = array();
    
            exec($cmd, $outputLog, $returnCode);
    
            if($returnCode != 0)
            {
                foreach($outputLog as $line)
                {
                    echo $line . "<br>";
                }
            }

            $query = $this->con->prepare("
            INSERT INTO thumbnails (videoId, filePath, selected)
            VALUES (:videoId, :filePath, :selected)
            ");

            $query->bindParam(":videoId", $videoId);
            $query->bindParam(":filePath", $fullThumbnailPath);
            $query->bindParam(":selected", $selected);

            $selected = $num == 1 ? 1 : 0;

            $success = $query->execute();

            if(!$success)
            {
                echo "Insert query failed!";
                return false; 
            }
        }
        return true; 
    }

    private function getVideoDuration($filePath)
    {
        return (int) shell_exec("$this->ffprobePath -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $filePath");
    }

    private function updateDuration($duration, $videoId)
    {

        $hours = floor($duration / 3600);
        $minutes = floor(($duration - ($hours * 3600)) / 60);
        $seconds = floor($duration % 60);

        if($hours < 1)
        {
            $hours = " ";
        }
        else
        {
            $hours = $hours . ":";
        }
        if($minutes < 10)
        {
            $minutes = "0" . $minutes . ":";
        }
        else
        {
            $minutes = $minutes . ":";
        }
        if($seconds < 10)
        {
            $seconds = "0" . $seconds;
        }
        else
        {
            $seconds = $seconds;
        }

        $duration = $hours.$minutes.$seconds;

        $query = $this->con->prepare("UPDATE videos SET duration=:duration WHERE id=:id");

        $query->bindParam(":duration", $duration);

        $query->bindParam(":id", $videoId);

        return $query->execute();
    }
}

?>