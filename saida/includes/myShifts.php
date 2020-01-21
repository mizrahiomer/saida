<?php
session_start();
require_once('init.php');
$uid = $_SESSION['uid'];
if (isset($_GET['month'])) {
    $month  = $_GET['month'];
    $year   = $_GET['year'];
    $sql    = "SELECT * FROM all_shifts WHERE MONTH(date) = '$month' AND YEAR(date) = '$year' AND uid='$uid'";
    $result = $database->query($sql);
    $data   = array();
    if ($result = $database->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        echo 0;
    }
} else if (isset($_GET['date'])) {
    $date    = $_GET['date'];
    $sql     = "SELECT tips_hour FROM shifts_summaries WHERE date='$date'";
    $result2 = $database->query($sql);
    $tips    = mysqli_fetch_assoc($result2);
    if ($result2) {
        echo $tips['tips_hour'];
    } else {
        echo 0;
    }
}
?>