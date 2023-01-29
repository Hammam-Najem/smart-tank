<?php 

    include "constants.php";
    include "connect-db.php";
    if($_SERVER['REQUSET_METHOD']="GET") {
        if ( isset($_GET["api_key"]) && isset($_GET["device_number"]) && ( isset($_GET["tank_height"]) || isset($_GET["blind_height"]) ) ) {
            $device_number = $_GET["device_number"];
            $api_key = $_GET["api_key"];
            // check api_key for security
          	if ($api_key != $security_api_key) {
          	    echo json_encode( array("status" => "fail", "message" => "API key is not correct !!"));
 	            return;
          	}
          	//check if the tank is exist
            $stmtcheck = $con->prepare("SELECT * FROM tank WHERE device_number = ?");
            $stmtcheck->execute(array($device_number));
            $tank = $stmtcheck->fetch();
            $row =$stmtcheck->rowcount();
            if ($row <= 0) {
                echo json_encode(array("status"=>"fail", "message"=>"Tank dose not exist !!"));
                return;
            }
            //get table configuration data
            $stmtcheck = $con->prepare("SELECT tank_configuration.* FROM tank, tank_configuration WHERE tank.id = ? and tank.id = tank_configuration.tank_id ORDER BY tank_configuration.created_at DESC LIMIT 1  ");
            $stmtcheck->execute(array($tank['id']));
            $tank_configuration = $stmtcheck->fetch(PDO::FETCH_ASSOC);
            $row =$stmtcheck->rowcount();
            if ($row > 0 ) {
             	if ( isset($_GET["tank_height"]) && isset($_GET["blind_height"]) ) {
                  	$tank_height = $_GET["tank_height"];
                  	$blind_height = $_GET["blind_height"];
                    $stmt=$con->prepare("INSERT INTO tank_configuration(`tank_height`, `blind_height`, `tank_id`) VALUES ( ?, ?, ?) ");
                    $stmt->execute(array($tank_height, $blind_height, $tank['id']));
                    $row =$stmt->rowcount();
                    if($row > 0) {
                 	    echo json_encode( array(
                 	        'status'=>"success", 
                 	        "message" => array(
                 	            "id" => $con->lastInsertId(),
                 	            "tank_height" => $tank_height, 
                 	            "blind_height" => $blind_height, 
                 	            "tank_id" => $tank['id'],
             	            ) 
         	            ));
                    }
                    else {
                        echo json_encode(array("status"=>"fail", "message"=>"Database internal error !!"));
                        return; 
                    }
              	} else if ( isset($_GET["tank_height"]) ) {
              	    $tank_height = $_GET["tank_height"];
                    $stmt=$con->prepare("INSERT INTO tank_configuration(`tank_height`, `blind_height`, `tank_id`) VALUES ( ?, ?, ?) ");
                    $stmt->execute(array($tank_height, $tank_configuration['blind_height'], $tank['id']));
                    $row =$stmt->rowcount();
                    if($row > 0) {
                 	    echo json_encode( array(
                 	        'status'=>"success", 
                 	        "message" => array(
                 	            "id" => $con->lastInsertId(),
                 	            "tank_height" => $tank_height, 
                 	            "blind_height" => $tank_configuration['blind_height'], 
                 	            "tank_id" => $tank['id'],
             	            ) 
         	            ));
                    }
                    else {
                        echo json_encode(array("status"=>"fail", "message"=>"Database internal error !!"));
                        return; 
                    }
              	} else if ( isset($_GET["blind_height"]) ) {
              	    $blind_height = $_GET["blind_height"];
                    $stmt=$con->prepare("INSERT INTO tank_configuration(`tank_height`, `blind_height`, `tank_id`) VALUES ( ?, ?, ?) ");
                    $stmt->execute(array($tank_configuration['tank_height'], $blind_height, $tank['id']));
                    $row =$stmt->rowcount();
                    if($row > 0) {
                 	    echo json_encode( array(
                 	        'status'=>"success", 
                 	        "message" => array(
                 	            "id" => $con->lastInsertId(),
                 	            "tank_height" => $tank_configuration['tank_height'], 
                 	            "blind_height" => $blind_height, 
                 	            "tank_id" => $tank['id'],
             	            ) 
         	            ));
                    }
                    else {
                        echo json_encode(array("status"=>"fail", "message"=>"Database internal error !!"));
                        return; 
                    }
              	} else {
              	    echo json_encode(array("status"=>"fail", "message"=>"Dead code reach !!"));
              	    return;
              	}
            } else {
                if (isset($_GET["tank_height"]) && isset($_GET["blind_height"])) {
                    $tank_height = $_GET["tank_height"];
                  	$blind_height = $_GET["blind_height"];
                    $stmt=$con->prepare("INSERT INTO tank_configuration(`tank_height`, `blind_height`, `tank_id`) VALUES ( ?, ?, ?) ");
                    $stmt->execute(array($tank_height, $blind_height, $tank['id']));
                    $row =$stmt->rowcount();
                    if($row > 0) {
                 	    echo json_encode( array(
                 	        'status'=>"success", 
                 	        "message" => array(
                 	            "id" => $con->lastInsertId(),
                 	            "tank_height" => $tank_height, 
                 	            "blind_height" => $blind_height, 
                 	            "tank_id" => $tank['id'],
             	            ) 
         	            ));
                    }
                    else {
                        echo json_encode(array("status"=>"fail", "message"=>"Database internal error !!"));
                        return; 
                    }
                } else {
                    echo json_encode(array("status"=>"fail", "message"=>"blind_height or blind_height didn't given !!"));
              	    return;
                }
            }
        } else {
            echo json_encode(array("status"=>"fail", "message"=>"Missing required field !!"));
        }
    } else {
        echo json_encode(array("status"=>"fail", "message"=>"Request should be GET !!"));
    }
?>