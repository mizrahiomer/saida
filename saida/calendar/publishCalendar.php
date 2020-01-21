<?php
session_start();
header('Content-Type: text/html; charset=utf-8');  
require_once ('../includes/init.php');
global $database;
$method = $_SERVER['REQUEST_METHOD'];


if ($method == 'POST'){
	$sql = "UPDATE users SET watched_calendar = 0";
	$result = $database->query($sql);
	if($result){
	    echo 1;
	}
	else{
	    echo 0;
	}
}
?>