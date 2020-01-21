<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
require('user.php');

if (isset($_POST)) {
    $user  = new User();
    $email = $_POST['email'];
    $uid   = $_POST['uid'];
    $login = $user->checkLogin($email, $uid);
    if ($login) {
        // Login Successful
        $_SESSION['uid'] = $uid;
        header("location: ../index.php");
        exit();
    } else {
        // Login Failed
        // echo 'Wrong username or password';
        $_SESSION['msg'] = '<div dir="rtl" class="alert alert-danger alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>איזה באסה, </strong>הזנת מייל/משתמש או סיסמה לא נכונים. נסה שוב.
        </div>';
        header("location: ../login.php");
        exit();
    }
}
?>