<?php 

    include "constants.php";
    include "connect-db.php";
    if($_SERVER['REQUSET_METHOD']="GET") {
        
      	$stmt=$con->prepare("SELECT * FROM br_tank ORDER BY created_at DESC");
        $stmt->execute();
        $badRequests= $stmt->fetchAll(PDO::FETCH_ASSOC);
        $row =$stmt->rowcount();
        if ($row >= 0) {
            echo json_encode( array(
     	        'status'=>"success", 
     	        "message" => array(
     	            "size" => count($badRequests),
     	            "bad_requests" => $badRequests,
 	            ) 
            ));
            return;
        } else {
            echo json_encode(array("status"=>"fail", "message"=>"Database internal error !!"));
            return;
        }
    
    } else {
        echo json_encode(array("status"=>"fail", "message"=>"Request should be GET !!"));
        return;
    }
?>