<?php

    $dsn = "mysql:host=awseb-e-grtep42r2s-stack-awsebrdsdatabase-5tvl8x5vlw4d.chtu2gwpamju.us-east-1.rds.amazonaws.com;dbname=ebdb";
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
