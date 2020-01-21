<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
require('init.php');

// initializing variables
$errors = array();

// connect to the database
$con = new Database();

// REGISTER USER
if (isset($_POST['addEmployee'])) {
    // receive all input values from the form
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $birth = $_POST['birth'];
    $role  = $_POST['role'];
    $uid   = $_POST['uid'];
    
    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($fname)) {
        array_push($errors, "First Name is required");
    }
    if (empty($lname)) {
        array_push($errors, "Last Name is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($birth)) {
        array_push($errors, "Birth Date is required");
    }
    if (empty($role)) {
        array_push($errors, "Role is required");
    }
    if (empty($uid)) {
        array_push($errors, "User ID is required");
    }
    
    /*// first check the database to make sure 
    // a user does not already exist with the same username and/or email
    $user_check_query = "SELECT * FROM users WHERE uid='$uid' OR email='$email' LIMIT 1";
    $result = mysqli_query($con, $user_check_query);
    $user = mysqli_fetch_assoc($result);
    
    if ($user) { // if user exists
    if ($user['uid'] === $uid) {
    array_push($errors, "User already exists");
    }
    
    if ($user['email'] === $email) {
    array_push($errors, "Email already exists");
    }
    }*/
    
    // Finally, register user if there are no errors in the form
    if (count($errors) == 0) {
        $result = User::add_user($fname, $lname, $email, $birth, $role, $uid);
        if ($result == NULL) {
            $_SESSION['uid'] = $uid;
            header('location: index.php');
        }
    }
}
?>