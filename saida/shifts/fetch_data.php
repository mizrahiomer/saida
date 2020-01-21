<?php
   // fetch_data.php
   
   $connect = new PDO("mysql:host=localhost;dbname=maorke_saida", "maorke", "P@j@Lp3-a=ZE8CE@");
   $connect->exec("set names utf8");
   $method = $_SERVER['REQUEST_METHOD'];
   
   if ($method == 'GET')
   	{
   	$data = array(
   		':fname' => "%" . $_GET['fname'] . "%",
   		':lname' => "%" . $_GET['lname'] . "%",
   		':date' => "%" . $_GET['date'] . "%",
   		':role' => "%" . $_GET['role'] . "%",
   		':uid' => "%" . $_GET['uid'] . "%",
   		':start' => "%" . $_GET['start'] . "%",
   		':end' => "%" . $_GET['end'] . "%",
   		':total' => "%" . $_GET['total'] . "%"
   	);
   	$query = "SELECT * FROM all_shifts WHERE fname LIKE :fname AND lname LIKE :lname AND date LIKE :date AND role LIKE :role AND uid LIKE :uid AND start LIKE :start AND end LIKE :end AND total LIKE :total ORDER BY date";
   	$statement = $connect->prepare($query);
   	$statement->execute($data);
   	$result = $statement->fetchAll();
   	foreach($result as $row)
   		{
   		$output[] = array(
   			'fname' => $row['fname'],
   			'lname' => $row['lname'],
   			'date' => date("d.m.Y", strtotime($row['date'])) ,
   			'role' => $row['role'],
   			'uid' => $row['uid'],
   			'start' => date("H:i:s", strtotime($row['start'])) ,
   			'end' => date("H:i:s", strtotime($row['end'])),
   			'total' => $row['total'],
   		);
   		}
   	header("Content-Type: application/json");
   	echo json_encode($output);
   	}
   
   if($method == "POST")
   {
    $data = array(
     ':fname'  => $_POST['fname'],
     ':lname'  => $_POST["lname"],
     ':date'   => date("Y-m-d", strtotime($_POST["date"])),
     ':role'   => $_POST["role"],
     ':uid'   => $_POST["uid"],
     'start' => date("H:i:s", strtotime($_POST['start'])) ,
     'end' => date("H:i:s", strtotime($_POST['end'])),
    );
    	if(date('H', strtotime($_POST['end'])) < (date('H', strtotime($_POST['start'])))){
   	    $newEnd = date("Y-m-d", strtotime($_POST["date"]. '+1 days'));
   	}
   	else{
   	    $newEnd = date("Y-m-d", strtotime($_POST["date"]));
   	}
   	date_default_timezone_set("Asia/Jerusalem");
        $query = "INSERT INTO all_shifts (fname, lname, date, role, uid, start, end, total) VALUES (:fname, :lname, :date, :role, :uid, concat(:date, ' ', :start), concat('$newEnd', ' ', :end), TIMEDIFF(end, start))";
        $statement = $connect->prepare($query);
        $statement->execute($data);
        echo 1;
   }
   
   if ($method == 'PUT')
   	{
   	parse_str(file_get_contents("php://input") , $_PUT);
   	$data = array(
   		':uid' => $_PUT['uid'],
   		':date' => date("Y-m-d", strtotime($_PUT["date"])),
   		':start' => date("H:i:s", strtotime($_PUT['start'])),
   		':end' => date("H:i:s", strtotime($_PUT['end']))
   	);
   	if(date('H', strtotime($_PUT['end'])) < (date('H', strtotime($_PUT['start'])))){
   	    $newEnd = date("Y-m-d", strtotime($_PUT["date"]. '+1 days'));
   	}
   	else{
   	    $newEnd = date("Y-m-d", strtotime($_PUT["date"]));
   	}
   	date_default_timezone_set("Asia/Jerusalem");
       $sql = "UPDATE all_shifts SET start = concat(:date, ' ', :start), end = concat('$newEnd', ' ', :end), total = TIMEDIFF(end, start), totald = (TIME_TO_SEC(total)/3600) WHERE uid = :uid AND date = :date";
   	$statement = $connect->prepare($sql);
   	$statement->execute($data);
   	echo 1;
   	}
   
   if ($method == "DELETE")
   	{
   	parse_str(file_get_contents("php://input") , $_DELETE);
   	$query = "DELETE FROM all_shifts WHERE uid = '" . $_DELETE["uid"] . "'";
   	$statement = $connect->prepare($query);
   	$statement->execute();
   	}
   ?>