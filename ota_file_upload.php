<?php

    include "connect-db.php";
    include "constants.php";
    
    mkdir( "OTAs/".$_POST["otaDayTime"] );
    $target_dir = "OTAs/".$_POST["otaDayTime"]."/";
    $target_file = $target_dir . basename($_FILES["otaFile"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    
    // Check file size
    if ($_FILES["otaFile"]["size"] > 5000000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    
    // Allow certain file formats
    if($imageFileType != "bin") {
        echo "Sorry, bin files are allowed.";
        $uploadOk = 0;
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["otaFile"]["tmp_name"], $target_file)) {
            try{
                $stmt=$con->prepare("INSERT INTO ota(`ota_date`,`ota_format`, `name`, `size`, `created_by`, `created_at`) VALUES ( ?, ?, ?, ?, ?, ?) ");
                $stmt->execute(array($_POST["otaDayTime"], $imageFileType, basename($_FILES["otaFile"]["name"]), $_FILES["otaFile"]["size"], $_POST["userName"], $local_server_timestamb));
                // echo "The file ". htmlspecialchars( basename( $_FILES["otaFile"]["name"])). " has been uploaded.";
                header('Location: front_end_screens/add_ota_page.html');
            } catch (Exception $e) {
                echo json_encode(array("status"=>"fail", "message"=> "exception:". $e->getMessage() ));
            }
        } else {
            echo "<h1>Sorry, there was an error uploading your file.</h1>";
        }
    }
?>