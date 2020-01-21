<?php
session_start();
require_once('init.php');
global $database;

$uid       = $_SESSION['uid'];
$sunday    = $_POST['sunday'];
$monday    = $_POST['monday'];
$tuesday   = $_POST['tuesday'];
$wednesday = $_POST['wednesday'];
$thursday  = $_POST['thursday'];
$friday    = $_POST['friday'];
$saturday  = $_POST['saturday'];

$sql    = "UPDATE requests SET sunday='" . $sunday . "', monday='" . $monday . "', tuesday='" . $tuesday . "', wednesday='" . $wednesday . "', thursday='" . $thursday . "', friday='" . $friday . "', saturday='" . $saturday . "' WHERE uid=$uid";
$result = $database->query($sql);
if (!$result) {
    echo 0;
} else {
    echo 1;
}
?>