<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
require_once('init.php');
global $database;

if (!empty($_POST['report'])) {
    $data = array();
    $sql  = "SELECT * FROM live_shift";
    if ($result = $database->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        echo 0;
    }
}

if (isset($_POST) && !empty($_POST['submit'])) {
    $date          = date("Y-m-d", strtotime($_POST['date']));
    $sql           = "SELECT SUM(totald) AS timeSum FROM all_shifts WHERE (date='$date' AND role != 'טבח' AND role != 'מארחת' AND role != 'מנהל')";
    $result        = $database->query($sql);
    $row           = $result->fetch_array();
    $tips_hour     = $row['timeSum'];
    $uid           = $_POST['uid'];
    $total         = $_POST['total'];
    $cash          = $_POST['cash'];
    $credit        = $_POST['credit'];
    $customers     = $_POST['customers'];
    $cancellations = $_POST['cancellations'];
    $discounts     = $_POST['discounts'];
    $tips          = $_POST['tips'];
    $summary       = $_POST['summary'];
    $customerAvg   = $total / $customers;
    $sql           = "INSERT INTO shifts_summaries(date, managerId, total, cash, credit, customers, cancellations, discounts, tips, summary, customer_avg, tips_hour) values ('" . $date . "', " . $uid . ", '" . $total . "', '" . $cash . "', '" . $credit . "', '" . $customers . "', '" . $cancellations . "', '" . $discounts . "', '" . $tips . "', '" . $summary . "', '" . $customerAvg . "','" . $tips_hour . "')";
    $result        = $database->query($sql);
    if (!$result) {
        echo 0;
    } else {
        echo $tips_hour;
    }
}
if (isset($_GET['reports'])) {
    $month = $_GET['month'];
    $year  = $_GET['year'];
    $sql   = "SELECT date FROM shifts_summaries WHERE MONTH(date)='$month' AND YEAR(date)='$year'";
    $data  = array();
    if ($result = $database->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        echo 0;
    }
}

if (isset($_POST['exceptional'])) {
    $uid    = $_POST['uid'];
    $sql    = "UPDATE users SET score = (score + 50) WHERE uid = '$uid'";
    $result = $database->query($sql);
    if (!$result) {
        echo 0;
    } else {
        echo 1;
    }
}
?>