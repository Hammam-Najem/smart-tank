<?php 

    include "connect-db.php";
    include "constants.php";
    if($_SERVER['REQUSET_METHOD']="GET") {
        if ( isset($_GET["imei"]) && isset($_GET["water_height"]) && isset($_GET["connect_time"]) && isset($_GET["signal_strength"]) && isset($_GET["connect_failures"]) && isset($_GET["bat_volt"]) && isset($_GET["ota_date"]) && isset($_GET["operator"])  ) {
          	$imei = $_GET["imei"];
          	$water_height = $_GET["water_height"];
          	$signal_strength = $_GET["signal_strength"];
          	$connect_time = $_GET["connect_time"];
          	$connect_failures = $_GET["connect_failures"];
          	$bat_volt = $_GET["bat_volt"];
          	$comment = isset($_GET["comment"]) ? $_GET["comment"] : null ;
          	$ota_date = $_GET["ota_date"];
          	$operator = $_GET["operator"];
          	$sleep_time = isset($_GET["sleep_time"]) ? $_GET["sleep_time"] : "0";
          	$raw_height = isset($_GET["raw_height"]) ? $_GET["raw_height"] : "0";
          	$failMessage = '';
          	// check operator
          	if ( $operator != 'JAWWAL' ) {
          	    $failMessage = "Operator error, operator: ".$operator.", expected is JAWWAL !!";
          	    addFailedTankStateRow($con, $imei, $operator, $water_height, $connect_time, $signal_strength, $connect_failures, $bat_volt, $comment, $ota_date, $local_server_timestamb, $failMessage, $sleep_time, $raw_height );
          	    echo json_encode(array("status"=>"fail", "message"=>$failMessage));
                return;
          	}
            try {
                // check IMEI authorization.
              	$stmt=$con->prepare("SELECT * FROM authorized_emie WHERE imei = ? ");
                $stmt->execute(array($imei));
                $row =$stmt->rowcount();
                if ($row <= 0) {
                    $failMessage = "IMEI not authorized !!";
                    addFailedTankStateRow($con, $imei, $operator, $water_height, $connect_time, $signal_strength, $connect_failures, $bat_volt, $comment, $ota_date, $local_server_timestamb, $failMessage, $sleep_time, $raw_height);
                    echo json_encode(array("status"=>"fail", "message"=> $failMessage));
                    return;
                }
                //check if the tank is exist
                $stmtcheck = $con->prepare("SELECT * FROM tank WHERE imei = ? ORDER BY tank.created_at DESC LIMIT 1");
                $stmtcheck->execute(array($imei));
                $tank = $stmtcheck->fetch();
                $row =$stmtcheck->rowcount();
                if ($row <= 0) {
                    $failMessage = "Tank dose not exist !!";
                    addFailedTankStateRow($con, $imei, $operator, $water_height, $connect_time, $signal_strength, $connect_failures, $bat_volt, $comment, $ota_date, $local_server_timestamb, $failMessage, $sleep_time, $raw_height);
                    echo json_encode(array("status"=>"fail", "message"=> $failMessage));
                    return;
                }
                incementTankStateRequestsNumber($con, $imei);
                $stmt=$con->prepare("INSERT INTO tank_state(`water_height_level`, `connect_time`, `signal_strength`, `connect_failures`, `bat_volt`, `comment`, `ota_date`, `tank_id`, `created_at`, `sleep_time`, `raw_height`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");
                $stmt->execute(array($water_height, $connect_time, $signal_strength, $connect_failures, $bat_volt, $comment, $ota_date, $tank['id'], $local_server_timestamb, $sleep_time, $raw_height));
                $row =$stmt->rowcount();
                if($row > 0)
                {
                    $last_ota = getAllOtas($con)[0];
                    $last_ota_date = $last_ota['ota_date'];
                    $last_ota_name = $last_ota['name'];
                    if ($ota_date != $last_ota_date) {
                        echo json_encode( array(
                 	        'status'=>"success", 
                 	        "message" => array(
                 	            "ota_need_update" => ($ota_date != $last_ota_date) ? "true" : "false",
                 	            "new_ota_date" => $last_ota_date,
                 	            "ota_host_url" => "smart_tank3-env.eba-rugg2xsp.us-east-1.elasticbeanstalk.com",
                 	            "ota_bin_uri" => "/smart_tank/OTAs/".$last_ota_date."/".$last_ota_name,
             	                "ota_full_url" => "smart_tank3-env.eba-rugg2xsp.us-east-1.elasticbeanstalk.com/smart_tank/OTAs/".$last_ota_date."/".$last_ota_name,
             	            ) 
         	            ));    
                    } else {
                        echo json_encode( array(
                 	        'status'=>"success", 
                 	        "message" => array(
                 	            "ota_need_update" => ($ota_date != $last_ota_date) ? "true" : "false",
             	            ) 
         	            ));
                    }
                }
                
            } catch (Exception $e) {
                echo json_encode(array("status"=>"fail", "message"=> "exception:". $e->getMessage() ));
            }
        } else {
            echo json_encode(array("status"=>"fail", "message"=>"Missing required field !!"));
        }
    } else {
        echo json_encode(array("status"=>"fail", "message"=>"Request should be GET !!"));
    }
?>
