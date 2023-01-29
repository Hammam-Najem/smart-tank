<?php 

    include "constants.php";
    include "connect-db.php";
    if($_SERVER['REQUSET_METHOD']="GET") {
        if ( isset($_GET["created_at"]) ) {
            $created_at = $_GET["created_at"];
          	try {
          	    $stmt=$con->prepare("DELETE FROM ota WHERE ota_date = ?");
                $stmt->execute(array($created_at));   
                if ($stmt->rowCount() > 0 ) {
                    $dir = "OTAs/".$created_at;
                    $files = glob($dir.'/*'); // get all file names
                    foreach($files as $file){ // iterate files
                        if(is_file($file)) {
                            unlink($file); // delete file
                        }
                    }
                    rmdir($dir); // remove directory after make it empty
                    echo json_encode( array(
             	        'status'=>"success", 
             	        "message" => "OTA created at ". $created_at. " Deleted. ".$retval,
                    ));
                    return;    
                } else {
                    echo json_encode( array(
             	        'status'=>"fail", 
             	        "message" => "Not deleted !!",
                    ));
                    return;
                }
                
          	} catch(PDOException $e){
          	    echo json_encode( array(
         	        'status'=>"fail", 
         	        "message" => $e->getMessage(),
                ));
                return;
                die("ERROR: Could not able to execute $sql. " . $e->getMessage());
            }
        } else {
            echo json_encode(array("status"=>"fail", "message"=>"Missing required field !!"));
            return;
        }
    } else {
        echo json_encode(array("status"=>"fail", "message"=>"Request should be GET !!"));
        return;
    }
?>