<?php 

    include "constants.php";
    include "connect-db.php";
    if($_SERVER['REQUSET_METHOD']="GET") {
        try {
      	    $stmt=$con->prepare("DELETE FROM br_tank_state");
            $stmt->execute(array($imei));   
            echo json_encode( array(
     	        'status'=>"success", 
     	        "message" => "Deleted !!",
            ));
            return;
            
      	} catch(PDOException $e){
      	    echo json_encode( array( 'status'=>"fail", "message" => $e->getMessage(), ));
            return;
        }
    } else {
        echo json_encode(array("status"=>"fail", "message"=>"Request should be GET !!"));
        return;
    }
?>