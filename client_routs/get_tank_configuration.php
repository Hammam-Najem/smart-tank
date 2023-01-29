<?php 

    include "../constants.php";
    include "../connect-db.php";
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        if ( isset($data->tank_id) ) {
            $tank_id = $data->tank_id;
            $listLength = isset($data->list_length) ? $data->list_length : null;
          	try{
          	    // check tank existace
              	$stmt=$con->prepare("SELECT * FROM tank WHERE id = ? ");
                $stmt->execute(array($tank_id));
                $tank= $stmt->fetch(PDO::FETCH_ASSOC);
                $row =$stmt->rowcount();
                if ($row < 0) {
                    echo json_encode(array("status"=>"fail", "message"=>"tank dose not exist !!"));
                    return;
                }
                echo json_encode( array(
         	        'status'=>"success", 
         	        "message" => $tank,
                ));
                return;
          	} catch (Exception $e) {
                echo json_encode(array("status"=>"fail", "message"=> "exception:". $e->getMessage() ));
            }
        } else {
            echo json_encode(array("status"=>"fail", "message"=>"Missing required field !!"));
            return;
        }
    } else {
        echo json_encode(array("status"=>"fail", "message"=>"Request should be POST !!"));
        return;
    }
?>