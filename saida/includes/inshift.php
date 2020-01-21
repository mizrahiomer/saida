<?php
session_start();
date_default_timezone_set("Asia/Jerusalem");
require_once('config.php');

require_once('database.php');

require_once('user.php');

global $database;
$method = $_SERVER['REQUEST_METHOD'];
$uid    = $_SESSION['uid'];
$fname  = $_SESSION['fname'];
$lname  = $_SESSION['lname'];
$role   = $_SESSION['role'];

if ($method == 'GET') {
    $sql    = "SELECT inshift FROM users WHERE uid='$uid'";
    $result = $database->query($sql);
    $row    = mysqli_fetch_array($result);
    echo $row['inshift'];
}

if ($method == "POST") {
    if (isset($_POST['flag'])) {
        $date   = date('Y-m-d');
        $sql    = "INSERT INTO live_shift (date, uid, fname, lname, role, start, end, total) VALUES ('" . $date . "', '" . $uid . "', '" . $fname . "', '" . $lname . "', '" . $role . "', NOW(), 0, 0)";
        $result = $database->query($sql);
        echo 'insert';
    } else {
        if (isset($_POST['end'])) {
            $end = date("H:i:s", strtotime($_POST['end']));
            $uid = $_POST['uid'];
        }
        $sql    = "SELECT start FROM live_shift WHERE uid = '$uid'";
        $result = $database->query($sql);
        $row    = mysqli_fetch_assoc($result);
        $start  = $row['start'];
        
        if (date('H', strtotime($end)) < (date('H', strtotime($start)))) {
            $newEnd = date("Y-m-d", strtotime($_POST["date"] . '+1 days'));
        } else {
            $newEnd = date("Y-m-d", strtotime($_POST["date"]));
        }
        
        if (isset($_POST['finish'])) {
            $end    = date("Y-m-d H:i:s");
            $newEnd = "";
        }
        
        $sql    = "UPDATE live_shift SET end = concat('$newEnd', ' ', '$end'), total = TIMEDIFF(end, '$start'), totald = (TIME_TO_SEC(total)/3600) WHERE uid = '$uid'";
        $result = $database->query($sql);
        $sql    = "INSERT INTO all_shifts (date, uid, fname, lname, role, start, end, total, totald) SELECT date, uid, fname, lname, role, start, end, total, totald FROM live_shift WHERE uid='$uid'";
        $result = $database->query($sql);
        $sql    = "DELETE FROM live_shift WHERE uid='$uid'";
        $result = $database->query($sql);
        echo 'end';
    }
    
    $inshift = $_POST['data'];
    $sql     = "UPDATE users SET inshift = '$inshift' WHERE uid = '$uid'";
    $result  = $database->query($sql);
}

?>