<?php 

    include "../constants.php";
    include "../connect-db.php";
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        if ( isset($data->device_number) && isset($data->password) ) {
          	$device_number = $data->device_number;
          	$password = $data->password;
          	//check if tank aleady exist
          	$stmt=$con->prepare("SELECT authorized_emie.password, tank.* FROM authorized_emie, tank WHERE tank.device_number = ? AND authorized_emie.password = ? AND tank.imei = authorized_emie.imei ORDER BY tank.created_at  DESC LIMIT 1;");
            $stmt->execute(array($device_number, $password));
            $tank = $stmt->fetch(PDO::FETCH_ASSOC);
            $row =$stmt->rowcount();
            if ($row > 0) {
 	            echo json_encode( array(
         	        'status'=>"success", 
         	        "message" => $tank, 
 	            ));
 	            return;
            } else {
                echo json_encode(array("status"=>"fail", "message"=>"Not authorized !!"));
                return;
            }
            
        } else {
            echo json_encode(array("status"=>"fail", "message"=>"Missing required field !!"));
            return;
        }
    
    } else {
        echo json_encode(array("status"=>"fail", "message"=>"Request should be POST !!"));
        return;
    }
    echo json_encode(array("status"=>"fail", "message"=>"Dead code reached !!"));
?>