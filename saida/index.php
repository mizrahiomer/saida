<?php
session_start();
require_once('includes/init.php');
$user    = new User();
$role    = $_SESSION['role'];
if (!$user->get_session()) {
    header("location:login.php");
    exit();
}
if (isset($_GET['q'])) {
    $user->user_logout();
    header("location:login.php");
    exit();
}
if ($role == 'מנהל') {
    header("location: stats/");
} else {
    header("location: myShifts/");
}
?>