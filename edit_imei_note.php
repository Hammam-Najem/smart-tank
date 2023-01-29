<?php 

    include "constants.php";
    include "connect-db.php";
    if($_SERVER['REQUSET_METHOD']="GET") {
        if ( isset($_GET["imei"]) && isset($_GET["note"]) ) {
          	$imei = $_GET["imei"];
          	$note = $_GET["note"];
            //check if imei aleady exist
          	$stmt=$con->prepare("SELECT * FROM authorized_emie WHERE imei = ? ");
            $stmt->execute(array($imei));
            $row =$stmt->rowcount();
            if ($row <= 0) {
                echo json_encode(array("status"=>"fail", "message"=>"IMEI does not exist !!"));
                return;
            }
            try {
                $stmt=$con->prepare("UPDATE authorized_emie SET note = ? WHERE imei = ?");
                $stmt->execute(array($note, $imei));
                $row =$stmt->rowcount();
                if ($row > 0) {
                    echo json_encode( array( 
                        "status" => "success",
                        "message" => "Note changed succsessfully."
                    ));
                } else {
                    echo json_encode( array( 
                        "status" => "fail",
                        "message" => "Note did not updated !!"
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
