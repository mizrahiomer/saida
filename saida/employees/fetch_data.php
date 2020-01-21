<?php

//fetch_data.php

$connect = new PDO("mysql:host=localhost;dbname=maorke_saida", "maorke", "P@j@Lp3-a=ZE8CE@");
$connect->exec("set names utf8");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    $data  = array(
        ':fname' => "%" . $_GET['fname'] . "%",
        ':lname' => "%" . $_GET['lname'] . "%",
        ':email' => "%" . $_GET['email'] . "%",
        ':birth' => "%" . $_GET['birth'] . "%",
        ':role' => "%" . $_GET['role'] . "%",
        ':uid' => "%" . $_GET['uid'] . "%",
        ':phone' => "%" . $_GET['phone'] . "%"
    );
    $query = "SELECT * FROM users WHERE fname LIKE :fname AND lname LIKE :lname AND email LIKE :email AND birth LIKE :birth AND role LIKE :role AND uid LIKE :uid AND phone LIKE :phone ORDER BY id";
    
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        $output[] = array(
            'id' => $row['id'],
            'fname' => $row['fname'],
            'lname' => $row['lname'],
            'email' => $row['email'],
            'birth' => date("d.m.Y", strtotime($row['birth'])),
            'role' => $row['role'],
            'uid' => $row['uid'],
            'entry_date' => date("d.m.Y", strtotime($row['entry_date'])),
            'phone' => $row['phone']
        );
    }
    header("Content-Type: application/json");
    echo json_encode($output);
}

if ($method == "POST") {
    $data      = array(
        ':fname' => $_POST['fname'],
        ':lname' => $_POST["lname"],
        ':email' => $_POST["email"],
        ':birth' => date("Y-m-d", strtotime($_POST["birth"])),
        ':role' => $_POST["role"],
        ':uid' => $_POST["uid"],
        ':password' => md5($_POST["uid"]),
        ':phone' => $_POST["phone"]
    );
    $query     = "SELECT uid FROM users";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $result = $statement->fetchAll();
    $flag   = FALSE;
    
    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $flag = TRUE;
    }
    foreach ($result as $row) {
        if ($row['uid'] == $_POST["uid"]) {
            $flag = TRUE;
        }
    }
    if ($flag) {
        echo 0;
    } else {
        if ($_POST['role'] == 'מנהל') {
            $bgcolor = "Aqua";
        } else if ($_POST['role'] == 'אחמ"ש') {
            $bgcolor = "Black";
        } else if ($_POST['role'] == 'מלצר') {
            $bgcolor = "Gold";
        } else if ($_POST['role'] == 'ברמן') {
            $bgcolor = "LimeGreen";
        } else if ($_POST['role'] == 'טבח') {
            $bgcolor = "CornflowerBlue";
        } else if ($_POST['role'] == 'מארחת') {
            $bgcolor = "BlueViolet";
        }
        $query     = "INSERT INTO users (fname, lname, email, birth, role, uid, password, phone, backgroundColor) VALUES (:fname, :lname, :email, :birth, :role, :uid, :password, :phone, '" . $bgcolor . "');
     INSERT INTO requests (uid, fname, lname, role, backgroundColor) VALUES (:uid, :fname, :lname, :role, '" . $bgcolor . "')";
        $statement = $connect->prepare($query);
        $statement->execute($data);
        echo 1;
    }
}

if ($method == 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);
    $data      = array(
        ':id' => $_PUT['id'],
        ':fname' => $_PUT['fname'],
        ':lname' => $_PUT['lname'],
        ':email' => $_PUT['email'],
        ':birth' => date("Y-m-d", strtotime($_PUT['birth'])),
        ':role' => $_PUT['role'],
        ':uid' => $_PUT['uid'],
        ':password' => md5($_PUT['uid']),
        ':phone' => $_PUT['phone']
    );
    $query     = "SELECT uid FROM users";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $result = $statement->fetchAll();
    $flag   = FALSE;
    if ($_PUT['role'] == 'מנהל') {
        $bgcolor = "Black";
    } else if ($_PUT['role'] == 'אחמ"ש') {
        $bgcolor = "Plum";
    } else if ($_PUT['role'] == 'מלצר') {
        $bgcolor = "Gold";
    } else if ($_PUT['role'] == 'ברמן') {
        $bgcolor = "PowderBlue";
    } else if ($_PUT['role'] == 'טבח') {
        $bgcolor = "DarkSlateBlue";
    } else if ($_PUT['role'] == 'מארחת') {
        $bgcolor = "Chartreuse";
    }
    $query     = "UPDATE users SET fname = :fname, lname = :lname, email = :email, birth = :birth, role = :role, uid = :uid, password = :password, phone = :phone, backgroundColor = '" . $bgcolor . "' WHERE id = :id";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    echo 1;
    
}
if ($method == "DELETE") {
    parse_str(file_get_contents("php://input"), $_DELETE);
    $query     = "DELETE FROM users WHERE id = '" . $_DELETE["id"] . "'";
    $statement = $connect->prepare($query);
    $statement->execute();
}
?>