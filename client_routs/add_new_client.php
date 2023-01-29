<?php 

    include "../constants.php";
    include "../connect-db.php";
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        if ( isset($data->phone) && isset($data->password)) {
          	$phone = $data->phone;
          	$password = $data->password;
          	try {
          	    //check if user aleady exist
              	$stmt=$con->prepare("SELECT * FROM client WHERE `phone` = ? ");
                $stmt->execute(array($phone));
                $row =$stmt->rowcount();
                if ($row > 0) {
                    echo json_encode(array("status"=>"fail", "message"=>"Client already exist !!"));
                    return;
                }
                $stmt=$con->prepare("INSERT INTO client (`phone`, `password`) VALUES (?, ?); ");
                $stmt->execute(array($phone, $password));
                $row =$stmt->rowcount();
                if($row > 0)
                {
             	    echo json_encode( array(
             	        'status'=>"success", 
             	        "message" => array(
             	            "id" => $con->lastInsertId(),
             	            "phone" => $phone,
             	            "password" => $password,
         	            ) 
     	            ));
     	            return;
                } else {
                    echo json_encode(array("status"=>"fail", "message"=>"Database error !!"));
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
        echo json_encode(array("status"=>"fail", "message"=>"Request should be POST !!"));
        return;
    }
?>