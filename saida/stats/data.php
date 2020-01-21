<?php
require_once('../includes/init.php');
global $database;
if (!$database) {
    die("Connection failed: " . $database->error);
}
$now = date('m');
if (isset($_GET['customers'])) {
    $sql    = "SELECT SUM(customers) AS customers FROM shifts_summaries WHERE MONTH(date)='$now'";
    $result = $database->query($sql);
    $data   = array();
    foreach ($result as $row) {
        $data[] = $row;
    }
} else if (isset($_GET['yearly'])) {
    $year   = $_GET['yearly'];
    $sql    = "SELECT SUM(customers) AS customers, MONTH(date) AS month FROM shifts_summaries WHERE YEAR(date)='$year' GROUP BY MONTH(date)";
    $result = $database->query($sql);
    $data   = array();
    foreach ($result as $row) {
        $data[] = $row;
    }
} else {
    //query to get data from the table
    $year = $_GET['year'];
    if (isset($_GET['month'])) {
        $month = $_GET['month'];
        $query = "SELECT DAY(date) AS day, SUM(total) AS sum FROM shifts_summaries WHERE MONTH(date)='$month' AND Year(date)='$year' GROUP BY DAY(date)";
    } else {
        $query = "SELECT MONTH(date) AS month, SUM(total) AS sum FROM shifts_summaries WHERE YEAR(date)='$year' GROUP BY MONTH(date)";
    }
    $result = $database->query($query);
    //loop through the returned data
    $data   = array();
    foreach ($result as $row) {
        $data[] = $row;
    }
    
    //now print the data
}
echo json_encode($data);
?>