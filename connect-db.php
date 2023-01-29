<?php

    $dsn = "mysql:host=localhost;dbname=technri2_smart_tank_db";
    $user = "technri2_default_user";
    $pass ="defaultUser123";
    $option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND =>"SET NAMES UTF8"
    );
    try{
    
    	$con = new PDO($dsn,$user,$pass,$option);
    	$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
    	echo $e -> getMessage();
    
    }
    
?>