<?php 

    include "constants.php";
    include "connect-db.php";
    if($_SERVER['REQUSET_METHOD']="GET") {
        $all_atas = getAllOtas($con);
        echo json_encode( array(
            'status'=>"success", 
            "message" => array(
                "size" => count($all_atas),
                "otas" => $all_atas,
            ) 
        ));
        return;
    } else {
        echo json_encode(array("status"=>"fail", "message"=>"Request should be GET !!"));
        return;
    }
?>