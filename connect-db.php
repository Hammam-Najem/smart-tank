<?php

    $dsn = "mysql:host=172.31.16.0;dbname=ebdb";
    $user = "admin";
    $pass ="technoUser123";
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