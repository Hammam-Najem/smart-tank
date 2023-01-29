<?php 

    include "../connect-db.php";
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        if ( isset($data->tank_id) && isset($data->password) ) {
          	$tank_id = $data->tank_id;
          	$password = $data->password;
            try {
                //check if the tank is exist
                $stmt =$con->prepare("SELECT * FROM tank WHERE id = ?");
            	$stmt->execute(array($tank_id));
            	$tank = $stmt->fetch();
            	$row =$stmt->rowcount();
                if ($row <= 0 ) {
                    echo json_encode(array('status'=>"fail", "message" => "tank_id not exist !!" ));
                    return;
                }
                $stmt=$con->prepare("SELECT authorized_emie.* FROM authorized_emie, tank WHERE tank.id = ? AND authorized_emie.imei = tank.imei;");
                $stmt->execute(array($tank_id));
                $imei= $stmt->fetch(PDO::FETCH_ASSOC);
                $stmt=$con->prepare("UPDATE authorized_emie SET password = ? WHERE authorized_emie.id = ?;");
                $stmt->execute(array($password, $imei['id']));
                $row =$stmt->rowcount();
                if ($row > 0) {
                    echo json_encode( array( 
                        "status" => "success",
                        "message" => "Settings changed succsessfully."
                    ));
                } else {
                    echo json_encode( array( 
                        "status" => "fail",
                        "message" => "Settings did not updated !!"
                    ));
                }
            } catch (Exception $e) {
                echo json_encode(array("status"=>"fail", "message"=> "exception:". $e->getMessage() ));
            }
            
        } else {
            echo json_encode(array("status"=>"fail", "message"=>"Missing required field !!"));
        }
    } else {
        echo json_encode(array("status"=>"fail", "message"=>"Request should be POST !!"));
    }
?>
