<?php
session_start();
require_once('init.php');

global $database;
$method = $_SERVER['REQUEST_METHOD'];
$uid    = $_SESSION['uid'];

if ($method == 'GET') {
    $sql    = "SELECT watched_calendar FROM users WHERE uid='$uid' LIMIT 1";
    $result = $database->query($sql);
    $row    = mysqli_fetch_array($result);
    if ($row['watched_calendar'] == '0') {
        $sql    = "UPDATE users SET watched_calendar = 1 WHERE uid='$uid'";
        $result = $database->query($sql);
    }
    echo $row['watched_calendar'];
}
?>