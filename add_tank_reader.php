<?php 

    include "constants.php";
    include "connect-db.php";
    if($_SERVER['REQUSET_METHOD']="GET") {
        if ( isset($_GET["imei"]) && isset($_GET["device_number"]) && isset($_GET["receiver_number"]) && isset($_GET["full_tank_height"]) && isset($_GET["dead_height"]) && isset($_GET["iccid"]) && isset($_GET["operator"]) ) {
          	$imei = $_GET["imei"];
          	$device_number = $_GET["device_number"];
          	$receiver_number = $_GET["receiver_number"];
          	$full_tank_height = $_GET["full_tank_height"];
          	$dead_height = $_GET["dead_height"];
          	$iccid = $_GET["iccid"];
          	$operator = $_GET["operator"];
          	$failMessage = '';
          	// check operator
          	if ( $operator != 'JAWWAL' ) {
          	    $failMessage = "Operator error, operator: ".$operator.", expected is JAWWAL !!";
          	    addFailedTankRow($con, $imei, $device_number, $receiver_number, $full_tank_height, $dead_height, $iccid, $local_server_timestamb, $failMessage );
          	    echo json_encode(array("status"=>"fail", "message"=> $failMessage ));
                return;
          	}
          	try {
          	    // check IMEI authorization.
              	$stmt=$con->prepare("SELECT * FROM authorized_emie WHERE imei = ? ");
                $stmt->execute(array($imei));
                $row =$stmt->rowcount();
                if ($row <= 0) {
                    $failMessage = "IMEI not authorized !!";
          	        addFailedTankRow($con, $imei, $device_number, $receiver_number, $full_tank_height, $dead_height, $iccid, $local_server_timestamb, $failMessage );
                    echo json_encode(array("status"=>"fail", "message"=> $failMessage ));
                    return;
                }
                incementTankReaderRequestsNumber($con, $imei);
              	//TODO: send request to JAWWAL.
                // $response = send_add_tank_reader_requist($device_number, $receiver_number, $full_tank_height);
                $stmt=$con->prepare("INSERT INTO tank(`imei`, `device_number`, `receiver_number`, `tank_height`, `dead_height`, `iccid`, `created_at`) VALUES ( ?, ?, ?, ?, ?, ?, ?) ");
                $stmt->execute(array($imei, $device_number, $receiver_number, $full_tank_height, $dead_height, $iccid, $local_server_timestamb ));
                $row =$stmt->rowcount();
                if($row > 0)
                {
             	    echo json_encode( array(
             	        'status'=>"success",
     	            ));
     	            return;
                } else {
                    $failMessage = "Database error !!";
          	        addFailedTankRow($con, $imei, $device_number, $receiver_number, $full_tank_height, $dead_height, $iccid, $local_server_timestamb, $failMessage );
                    echo json_encode(array("status"=>"fail", "message"=> $failMessage ));
                    return;
                }    
          	} catch (Exception $e) {
                // not a MySQL exception
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