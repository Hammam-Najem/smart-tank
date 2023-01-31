<?php
    $local_server_timestamb = date("Y-m-d H:i:s", strtotime('+2 hours'));
    
    
    function incementTankStateRequestsNumber($con, $imei) {
        try {
            $stmt=$con->prepare("UPDATE authorized_emie SET tank_state_requests_number = tank_state_requests_number + 1 WHERE imei = ?;");
            $stmt->execute(array($imei));
        } catch (MySQLException $e) {
            // mysql exception
            echo json_encode(array("status"=>"fail", "message"=> "mysql exception:". $e->getMessage() ));
        }
        catch (Exception $e) {
            // not a MySQL exception
            echo json_encode(array("status"=>"fail", "message"=> "exception:". $e->getMessage() ));
        }
        return;
    }
    
    function incementTankReaderRequestsNumber($con, $imei) {
        try {
            $stmt=$con->prepare("UPDATE authorized_emie SET tank_reader_requests_number = tank_reader_requests_number + 1 WHERE imei = ?;");
            $stmt->execute(array($imei));
        } catch (MySQLException $e) {
            // mysql exception
            echo json_encode(array("status"=>"fail", "message"=> "mysql exception:". $e->getMessage() ));
        }
        catch (Exception $e) {
            // not a MySQL exception
            echo json_encode(array("status"=>"fail", "message"=> "exception:". $e->getMessage() ));
        }
        return;
    }
    
    function getAllOtas($con) {
        try {
            $stmt=$con->prepare("SELECT * From ota ORDER BY created_at DESC;");
            $stmt->execute(array());
            $ota_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo json_encode(array("status"=>"fail", "message"=> "exception:". $e->getMessage() ));
        }
        return $ota_list;
    }
    
    function addFailedTankRow($con, $imei, $device_number, $receiver_number, $full_tank_height, $dead_height, $iccid, $created_at, $failMessage) {
        try {
            $stmt=$con->prepare("INSERT INTO br_tank(`imei`, `device_number`, `receiver_number`, `tank_height`, `dead_height`, `iccid`, `created_at`, `fail_reason`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?) ");
            $stmt->execute(array($imei, $device_number, $receiver_number, $full_tank_height, $dead_height, $iccid, $created_at, $failMessage ));
        } catch (Exception $e) {
            echo json_encode(array("status"=>"fail", "message"=> "exception:". $e->getMessage() ));
        }
        return;
    }
    
    function addFailedTankStateRow($con, $imei, $operator, $water_height, $connect_time, $signal_strength, $connect_failures, $bat_volt, $comment, $ota_date, $created_at, $failMessage, $sleep_time, $raw_height) {
        try {
            $stmt=$con->prepare("INSERT INTO br_tank_state(`imei`, `operator`, `water_height_level`, `connect_time`, `signal_strength`, `connect_failures`, `bat_volt`, `comment`, `ota_date`, `created_at`, `fail_reason`, `sleep_time`, `raw_height`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");
            $stmt->execute(array($imei, $operator, $water_height, $connect_time, $signal_strength, $connect_failures, $bat_volt, $comment, $ota_date, $created_at, $failMessage, $sleep_time, $raw_height));
        } catch (Exception $e) {
            echo json_encode(array("status"=>"fail", "message"=> "exception:". $e->getMessage() ));
        }
        return;
    }
    
    
    function send_add_tank_reader_requist($device_number, $receiver_number, $tank_height) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://hesabicorporateapis.jawwal.ps/api/Tank/AddTankReader',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "DeviceMSISDN": $device_number,
            "RecieverMSISDN": $receiver_number,
            "TankHeightInCm": $tank_height
        }',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Basic VGFua01hbmFnZW1lbnQ6dSEmQUM3SnBvQjZSNkEyQF4=',
            'Content-Type: application/json',
            'Cookie: BIGipServer~DMZ-WEBSERVICES~MYACCOUNT-WEB-80=rd80o00000000000000000000ffff0a67c857o80; TS0108ef38=01a6c0e57cbd5b8eb1c02541ff1c2267d31c42b40826be1f729370fdc95ade8367cba8871cd434a48573c44ec3445e6a5ad93ef6d1ddae952f3764376f32618d45cda84631'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return $response;
    }

?>
