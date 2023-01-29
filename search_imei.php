<?php 

    include "constants.php";
    include "connect-db.php";
    if($_SERVER['REQUEST_METHOD']==="GET") {
        if ( isset($_GET["sub_string"]) ) {
            $sub_string = $_GET["sub_string"];
            $foundImeis = [];
            try{
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
                        tank.imei = authorized_emie.imei AND
                        (
                            authorized_emie.imei LIKE '%".$sub_string."%' OR
                            authorized_emie.note LIKE '%".$sub_string."%' OR
                            authorized_emie.password LIKE '%".$sub_string."%' OR
                            tank.imei LIKE '%".$sub_string."%' OR
                            tank.device_number LIKE '%".$sub_string."%' OR
                            tank.receiver_number LIKE '%".$sub_string."%'
                        )
                    ORDER BY tank.created_at DESC
                    ;
                ");
                $stmt->execute(array());
                $foundImeisWithTank= $stmt->fetchAll(PDO::FETCH_ASSOC);
                // get imeis that doesn't have tanks
                $stmt=$con->prepare("
                    SELECT
                        *
                    FROM
                        authorized_emie
                    WHERE
                    	(
                    	    authorized_emie.imei LIKE '%".$sub_string."%' OR
                            authorized_emie.note LIKE '%".$sub_string."%' OR
                            authorized_emie.password LIKE '%".$sub_string."%'
                        ) AND
                    	authorized_emie.imei NOT IN (
                        	SELECT 
                            	tank.imei
                            FROM
                            	tank
                        )
                    ;
                ");
                $stmt->execute();
                $foundImeisWithoutTank= $stmt->fetchAll(PDO::FETCH_ASSOC);
                $foundImeis = array_merge($foundImeisWithTank, $foundImeisWithoutTank);
                echo json_encode( array(
         	        'status'=>"success", 
         	        "message" => array(
         	            "size" => count($foundImeis),
         	            "imeis" => $foundImeis,
     	            ) 
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
        echo json_encode(array("status"=>"fail", "message"=>"Request should be GET !!"));
        return;
    }
?>