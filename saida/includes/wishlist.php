<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once('init.php');
global $database;
$method = $_SERVER['REQUEST_METHOD'];
$uid    = $_SESSION['uid'];
if ($method == 'POST') {
    $action = $_POST['action'];
    $gid    = $_POST['gid'];
    if ($action == "add") {
        $sql    = "INSERT INTO wishlist VALUES ($uid,$gid,0)";
        $result = $database->query($sql);
    } else if ($action == "delete") {
        $sql    = "DELETE FROM wishlist WHERE uid=$uid AND gid=$gid";
        $result = $database->query($sql);
    }
    echo $result;
}

if ($method == 'GET') {
    if (isset($_GET['score'])) {
        $sql= "SELECT score FROM users WHERE uid = $uid";
        $result = $database->query($sql);
        $row= $result->fetch_assoc();
        $_SESSION['score'] = $row['score'];
        echo $row['score'];
    } else {
        $gid    = $_GET['gid'];
        $sql    = "SELECT * FROM wishlist WHERE (uid=$uid AND gid=$gid AND purchased=false)";
        $result = $database->query($sql);
        if ($result->num_rows > 0) {
            echo 1;
        } else {
            echo 0;
        }
    }
}
if ($method == 'PUT') {
    $sql    = "SELECT gid FROM wishlist WHERE uid=$uid AND purchased=false";
    $result = $database->query($sql);
    $rows   = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
}
?>