<?php
session_start();
require_once 'init.php';
$uid       = $_SESSION['uid'];
$statusMsg = '';
$method    = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST') {
    $name        = $_POST['name'];
    $amount      = $_POST['amount'];
    $price       = $_POST['price'];
    $description = $_POST['description'];
    $gid         = $_POST['gid'];
    if (!empty($_FILES["image"]["name"])) {
        $targetDir      = "../gifts/img/";
        $fileName       = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType       = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        // Allow certain file formats
        $allowTypes     = array(
            'jpg',
            'png',
            'jpeg',
            'gif'
        );
        if (in_array($fileType, $allowTypes)) {
            // Upload file to server
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                // Insert image file name into database
            }
        }
        if (isset($_POST['gid'])) {
            $sql = "UPDATE gifts SET name = '$name', description = '$description', amount = '$amount', price = '$price', image = '$fileName' WHERE id = '$gid'";
        } else {
            $sql = "INSERT INTO gifts (name, description,amount, price, image) VALUES ('" . $name . "', '" . $description . "','" . $amount . "', '" . $price . "', '" . $fileName . "')";
        }
    } else {
        $sql = "UPDATE gifts SET name = '$name', description = '$description', amount = '$amount', price = '$price' WHERE id = '$gid'";
    }
    $result = $database->query($sql);
    echo $_POST['gid'];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

if ($method == 'GET') {
    $gid    = $_GET['gid'];
    $delete = $_GET['delete'];
    if (isset($delete)) {
        $sql    = "DELETE FROM gifts WHERE id=$gid";
        $result = $database->query($sql);
        $sql    = "DELETE FROM wishlist WHERE gid=$gid";
        $result = $database->query($sql);
    } else if (isset($_GET['buy'])) {
        $sql = "INSERT INTO wishlist (uid,gid) VALUES ('".$uid."','".$gid."')";
        $result = $database->query($sql);
        $sql    = "UPDATE wishlist SET purchased = true WHERE gid = '$gid' AND uid = '$uid'";
        $result = $database->query($sql);
        $sql    = "UPDATE users SET score = (score - (SELECT price FROM gifts WHERE id = '$gid')) WHERE uid = '$uid'";
        $result = $database->query($sql);
        $sql    = "UPDATE gifts SET amount = amount-1 WHERE id = '$gid'";
        $result = $database->query($sql);
        if (!$result) {
            echo 0;
        } else {
            echo 1;
        }
    } else if (isset($_GET['view'])) {
        $sql    = "SELECT fname,lname,uid FROM users WHERE uid IN (SELECT uid FROM wishlist WHERE purchased=true AND gid=$gid)";
        $result = $database->query($sql);
        $rows   = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        echo json_encode($rows, JSON_UNESCAPED_UNICODE);
    }
    
    else if (isset($_GET['status'])) {
        $uid    = $_GET['uid'];
        $gid    = $_GET['gid'];
        $sql    = "DELETE FROM wishlist WHERE uid=$uid AND gid=$gid";
        $result = $database->query($sql);
        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    } else {
        $sql    = "SELECT * FROM gifts where id=$gid";
        $result = $database->query($sql);
        $rows   = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        echo json_encode($rows, JSON_UNESCAPED_UNICODE);
    }
}
?>