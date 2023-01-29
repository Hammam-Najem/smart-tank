<?php 

    include "constants.php";
    include "connect-db.php";
    if($_SERVER['REQUEST_METHOD']==="GET") {
        if ( isset($_GET["name"]) && isset($_GET["password"])) {
          	$name = $_GET["name"];
          	$password = $_GET["password"];
          	//check if user aleady exist
          	$stmt=$con->prepare("SELECT * FROM user WHERE name = ? ");
            $stmt->execute(array($name));
            $row =$stmt->rowcount();
            if ($row > 0) {
                echo json_encode(array("status"=>"fail", "message"=>"User already exist !!"));
                return;
            }
            $stmt=$con->prepare("INSERT INTO user (`name`, `password`) VALUES (?, ?); ");
            $stmt->execute(array($name, $password));
            $row =$stmt->rowcount();
            if($row > 0)
            {
         	    echo json_encode( array(
         	        'status'=>"success", 
         	        "message" => array(
         	            "id" => $con->lastInsertId(),
         	            "name" => $name,
         	            "password" => $password,
     	            ) 
 	            ));
 	            return;
            } else {
                echo json_encode(array("status"=>"fail", "message"=>"Database error !!"));
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
?>
