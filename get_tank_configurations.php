<?php 

    include "constants.php";
    include "connect-db.php";
    if($_SERVER['REQUSET_METHOD']="GET") {
        if ( isset($_GET["imei"])) {
            $imei = $_GET["imei"];
          	// check imei existace
          	$stmt=$con->prepare("SELECT * FROM authorized_emie WHERE imei = ? ");
            $stmt->execute(array($imei));
            $imei_array= $stmt->fetchAll(PDO::FETCH_ASSOC);
            $row =$stmt->rowcount();
            if ($row < 0) {
                echo json_encode(array("status"=>"fail", "message"=>"IMEI dose not exist !!"));
                return;
            }
            
            // get imei tank configurations
            $stmt=$con->prepare("SELECT tank.* FROM tank, authorized_emie WHERE tank.imei = ? and tank.imei = authorized_emie.imei ORDER BY tank.created_at DESC");
            $stmt->execute(array($imei));
            $tanks= $stmt->fetchAll(PDO::FETCH_ASSOC);
            $row =$stmt->rowcount();
            echo json_encode( array(
     	        'status'=>"success", 
     	        "message" => array(
     	            "size" => count($tanks),
     	            "tanks" => $tanks,
 	            ) 
            ));
            return;
            
        } else {
            echo json_encode(array("status"=>"fail", "message"=>"Missing required field !!"));
            return;
        }
    } else {
        echo json_encode(array("status"=>"fail", "message"=>"Request should be GET !!"));
        return;
    }
?>