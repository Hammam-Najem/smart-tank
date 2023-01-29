<?php 

    include "constants.php";
    include "connect-db.php";
    if($_SERVER['REQUSET_METHOD']="GET") {
        if ( isset($_GET["imei"]) && isset($_GET["note"]) ) {
          	$imei = $_GET["imei"];
          	$note = $_GET["note"];
          	//check if imei aleady exist
          	$stmt=$con->prepare("SELECT * FROM authorized_emie WHERE imei = ? ");
            $stmt->execute(array($imei));
            $row =$stmt->rowcount();
            if ($row > 0) {
                echo json_encode(array("status"=>"fail", "message"=>"IMEI already exist !!"));
                return;
            }
            $stmt=$con->prepare("INSERT INTO authorized_emie (`imei`, `note`) VALUES (?, ?); ");
            $stmt->execute(array($imei, $note));
            $row =$stmt->rowcount();
            if($row > 0)
            {
         	    echo json_encode( array(
         	        'status'=>"success", 
         	        "message" => array(
         	            "id" => $con->lastInsertId(),
         	            "imei" => $imei,
         	            "note" => $note,
         	            "password" => "0000",
     	            ) 
 	            ));
 	            return;
            } else {
                echo json_encode(array("status"=>"fail", "message"=>"Database error !!"));
                return;
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