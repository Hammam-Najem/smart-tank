<?php 

    include "constants.php";
    include "connect-db.php";
    if($_SERVER['REQUSET_METHOD']="GET") {
        if ( isset($_GET["imei"])) {
            $imei = $_GET["imei"];
            $listLength = isset($_GET["list_length"]) ? $_GET["list_length"] : null;
          	// check imei existace
          	$stmt=$con->prepare("SELECT * FROM authorized_emie WHERE imei = ? ");
            $stmt->execute(array($imei));
            $imei_array= $stmt->fetchAll(PDO::FETCH_ASSOC);
            $row =$stmt->rowcount();
            if ($row < 0) {
                echo json_encode(array("status"=>"fail", "message"=>"IMEI dose not exist !!"));
                return;
            }
            
            // get imei tank states
            if (is_null($listLength)){
                $stmt=$con->prepare("SELECT tank_state.* FROM tank_state, tank WHERE tank.imei = ? and tank.id = tank_state.tank_id ORDER BY tank_state.created_at DESC");    
            } else {
                $stmt=$con->prepare("SELECT tank_state.* FROM tank_state, tank WHERE tank.imei = ? and tank.id = tank_state.tank_id ORDER BY tank_state.created_at DESC LIMIT ". $listLength);
            }
            $stmt->execute(array($imei));
            $states= $stmt->fetchAll(PDO::FETCH_ASSOC);
            $row =$stmt->rowcount();
            echo json_encode( array(
     	        'status'=>"success", 
     	        "message" => array(
     	            "size" => count($states),
     	            "states" => $states,
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