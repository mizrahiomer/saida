<?php

//load.php

$connect = new PDO('mysql:host=localhost;dbname=maorke_saida', 'maorke', 'P@j@Lp3-a=ZE8CE@');
$connect->exec('set names utf8');

$data = array();

$query = "SELECT * FROM events ORDER BY id";


$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

foreach ($result as $row) {
    $data[] = array(
        'id' => $row["id"],
        'title' => $row["title"],
        'start' => $row["start_event"],
        'backgroundColor' => $row["backgroundColor"],
        'end' => $row["end_event"]
    );
}

echo json_encode($data);

?>