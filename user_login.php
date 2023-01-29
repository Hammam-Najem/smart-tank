<?php 

    include "constants.php";
    include "connect-db.php";
    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ( $_GET['user_name'] && $_GET['password'] ) {
          	$user_name = $_GET['user_name'];
          	$password = $_GET['password'];
          	//check if client aleady exist
          	$stmt=$con->prepare("SELECT * FROM user WHERE name = ? and password = ?");
            $stmt->execute(array($user_name, $password));
            $client = $stmt->fetch();
            $row =$stmt->rowcount();
            if ($row > 0) {
                echo json_encode( array(
         	        'status'=>"success", 
         	        "message" => array(
         	            "id" => $client['id'],
         	            "user_name" => $client['user_name'],
         	            "password" => $client['password'],
     	            ) 
 	            ));
 	            return;
            } else {
                echo json_encode(array("status"=>"fail", "message"=>"Not authorized !!"));
                return;
            }
            
        } else {
            echo json_encode(array("status"=>"fail", "message"=>"Missing required field !!"));
            return;
        }
    
    } else {
        echo json_encode(array("status"=>"fail", "message"=>"Request should be GET !!"));
        return;
    }
    echo json_encode(array("status"=>"fail", "message"=>"Dead code reached !!"));
?>