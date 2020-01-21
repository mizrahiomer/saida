<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once('init.php');
global $database;
$method = $_SERVER['REQUEST_METHOD'];
$uid    = $_SESSION['uid'];

if ($method == 'GET') {
    $sql    = "SELECT * FROM users WHERE role= 'מלצר' OR role= 'טבח' OR role= 'מארחת' OR role= 'ברמן' ORDER BY score DESC";
    $result = $database->query($sql);
    $rows   = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
}
?>