<?php 

    include "../constants.php";
    include "../connect-db.php";
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        if ( isset($data->tank_id) ) {
            $tank_id = $data->tank_id;
            $listLength = isset($data->list_length) ? $data->list_length : null;
          	// check tank existace
          	$stmt=$con->prepare("SELECT * FROM tank WHERE id = ? ");
            $stmt->execute(array($tank_id));
            $row =$stmt->rowcount();
            if ($row < 0) {
                echo json_encode(array("status"=>"fail", "message"=>"tank dose not exist !!"));
                return;
            }
            // get tank tank states
            if (is_null($listLength)){
                $stmt=$con->prepare("SELECT tank_state.*, tank.tank_height FROM tank_state, tank WHERE tank.id = ? AND tank.id = tank_state.tank_id ORDER BY tank_state.created_at DESC");    
            } else {
                $stmt=$con->prepare("SELECT tank_state.*, tank.tank_height FROM tank_state, tank WHERE tank.id = ? AND tank.id = tank_state.tank_id ORDER BY tank_state.created_at DESC LIMIT ". $listLength);
            }
            $stmt->execute(array($tank_id));
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
        echo json_encode(array("status"=>"fail", "message"=>"Request should be POST !!"));
        return;
    }
?>