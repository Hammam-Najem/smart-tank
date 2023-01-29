<?php 

    include "constants.php";
    include "connect-db.php";
    if($_SERVER['REQUSET_METHOD']="GET") {
        if ( isset($_GET["imei"]) ) {
            $imei = $_GET["imei"];
          	try {
          	    $stmt=$con->prepare("DELETE FROM authorized_emie WHERE imei = ?");
                $stmt->execute(array($imei));   
                if ($stmt->rowCount() > 0 ) {
                    echo json_encode( array(
             	        'status'=>"success", 
             	        "message" => $imei. " Deleted.",
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