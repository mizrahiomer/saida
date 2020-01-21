<?php
require_once('../includes/init.php');
$role    = $_REQUEST['dropdownValue'];
$sql1    = "SELECT backgroundColor FROM users WHERE role = '$role' LIMIT 1";
$result1 = $database->query($sql1);
$row     = mysqli_fetch_array($result1);
$bgcolor = $row['backgroundColor'];
$sql     = "SELECT * FROM requests WHERE backgroundColor = '$bgcolor'";
$result  = $database->query($sql);
$rows    = array();
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}
print json_encode($rows, JSON_UNESCAPED_UNICODE);

?>