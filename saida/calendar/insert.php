<?php
//insert.php

$connect = new PDO('mysql:host=localhost;dbname=maorke_saida', 'maorke', 'P@j@Lp3-a=ZE8CE@');
$connect->exec('set names utf8');

if (isset($_POST["title"])) {
    $query     = "
 INSERT INTO events 
 (title, start_event, end_event, backgroundColor,uid) 
 VALUES (:title, :start_event, :end_event, :backgroundColor,:uid)
 ";
    $statement = $connect->prepare($query);
    $statement->execute(array(
        ':title' => $_POST['title'],
        ':start_event' => $_POST['start'],
        ':backgroundColor' => $_POST["backgroundColor"],
        ':end_event' => $_POST['end'],
        ':uid' => $_POST['uid']
    ));
}


?>