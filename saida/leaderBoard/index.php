<?php  
   session_start();
   require_once ('../includes/init.php');
   $user = new User();
   $uid = $_SESSION['uid'];
   if (!$user->get_session()){
   header("location:login.php");
   exit();
   }
   if (isset($_GET['q'])){
   $user->user_logout();
   header("location:login.php");
   exit();
   }
   ?>
<!doctype html>
<html>
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!-- Custom Bootstrap CSS -->
      <link rel="stylesheet" href="https://bootswatch.com/4/minty/bootstrap.css">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
      <link rel="stylesheet" href="../sidebar/sidebar.css" />
      <link rel="stylesheet" href="index.css">
   </head>
   <title>טבלת מובילים סעידה</title>
   <body>
      <?php include "../sidebar/sidebarH.php"; ?>
      <div class="text-center">
         <h1>טבלת מובילים</h1>
      </div>
      <div class="container">
         <table class="table shadow" dir="rtl">
            <tbody></tbody>
         </table>
      </div>
      <?php include "../sidebar/sidebarF.php"; ?>
      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="https://code.jquery.com/jquery-3.4.0.min.js" integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg=" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script src="script.js"></script>
   </body>