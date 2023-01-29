<?php 

    include "constants.php";
    include "connect-db.php";
    if($_SERVER['REQUEST_METHOD']=== "GET") {
      	$imei_array = [];
      	try {
      	    // get all imeis that have tanks
      	    $stmt=$con->prepare("
                SELECT 
                	tank.device_number,
                    tank.receiver_number,
                    tank.created_at,
                    authorized_emie.*
                FROM
                	authorized_emie,
                	tank,
                	(
                    	SELECT 
                            tank.*, MAX(tank.created_at) as `recent_created_at`
                        FROM
                            tank
                        GROUP BY tank.imei
                    ) as recent_tank
                WHERE 
                	tank.created_at = recent_tank.recent_created_at AND
                    tank.imei = authorized_emie.imei
                ORDER BY tank.created_at DESC
                ;
            ");
            $stmt->execute();
            $imei_with_tank_array= $stmt->fetchAll(PDO::FETCH_ASSOC);
            // get imeis that doesn't have tanks
            $stmt=$con->prepare("
                SELECT
                    *
                FROM
                    authorized_emie
                WHERE
                	authorized_emie.imei NOT IN (
                    	SELECT 
                        	tank.imei
                        FROM
                        	tank
                    )
                ;
            ");
            $stmt->execute();
            $imei_without_tank_array= $stmt->fetchAll(PDO::FETCH_ASSOC);
            $imei_array = array_merge($imei_with_tank_array, $imei_without_tank_array);
            echo json_encode( array(
     	        'status'=>"success", 
     	        "message" => array(
     	            "size" => count($imei_array),
     	            "imeis" => $imei_array,
                 ) 
            ));
            return;
      	} catch (Exception $e) {
            echo json_encode(array("status"=>"fail", "message"=> "exception:". $e->getMessage() ));
        }
    } else {
        echo json_encode(array("status"=>"fail", "message"=>"Request should be GET !!"));
        return;
    }
?>