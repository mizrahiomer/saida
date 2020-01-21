<?php  
   session_start();
   require_once ('../includes/init.php');
   $user = new User();
   $role = $_SESSION['role'];
   if (!$user->get_session()) {
    header("location:../login.php");
    exit();
    }
    if($role != 'מנהל'){
        header("location: ../index.php");
        exit();
    }
    if (isset($_GET['q'])) {
        $user->user_logout();
        header("location:../login.php");
        exit();
    }
   $uid = $_SESSION['uid'];
   $query = "SELECT YEAR(date) AS year FROM shifts_summaries GROUP BY YEAR(date) ORDER BY date DESC";
   $years = $database->query($query);
   $query = "SELECT MONTH(date) AS month FROM shifts_summaries GROUP BY MONTH(date) ORDER BY date ";
   $month = $database->query($query);
   
   ?>
<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!-- Bootstrap CSS -->
      <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/minty/bootstrap.min.css" rel="stylesheet" integrity="sha384-9NlqO4dP5KfioUGS568UFwM3lbWf3Uj3Qb7FBHuIuhLoDp3ZgAqPE1/MYLEBPZYM" crossorigin="anonymous">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
      <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
      <link rel="stylesheet" href="../sidebar/sidebar.css" />
      <title>סטטיסטיקות</title>
   </head>
   <style>
      .custom-select {
      width: 30%;
      }
      canvas {
      max-width: 100%;
      height:80%;
      margin:auto;
      }
   </style>
   <body>
      <?php include "../sidebar/sidebarH.php"; ?>
      <!-- Page Wrapper -->
      <div id="wrapper">
         <!-- Content Wrapper -->
         <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
               <!-- Begin Page Content -->
               <div class="container text-center">
                  <!-- Page Heading -->
                  <h1 class="h3 mb-2 text-gray-800">סטטיסטיקות של סעידה</h1>
                  <br>
                  <!-- Content Row -->
                  <div class="justify-content-center">
                     <!-- Earnings (Weekly) Card Example -->
                     <div class="row mb-4 justify-content-center text-center">
                        <div class="col-sm-5 h-100 m-1 card shadow py-2">
                           <div class="card-body">
                              <div class="no-gutters align-items-center">
                                 <div class="col">
                                    <div class="h5 font-weight-bold text-primary mb-2">סך הכנסות לחודש זה</div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800">&#8362; <span id="monthlyIncome"></span></div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-5 h-100 m-1 card shadow py-2">
                           <div class="card-body">
                              <div class="no-gutters align-items-center">
                                 <div class="col">
                                    <div class="h5 font-weight-bold text-primary mb-2">סך לקוחות לחודש זה</div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800"><span id="monthlyCst"></span></div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- Donut Chart -->
                     <div class="container text-center card shadow mb-4 p-0 text-right">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3">
                           <h5 class="m-1 font-weight-bold text-primary">
                           כמות לקוחות לפי חודשים בשנת  
                           <h5>
                           <select class="custom-select" id="yearlyCst">
                           <?php 
                              $years->data_seek(0);
                              while($row = mysqli_fetch_assoc($years)) {
                                 echo '<option value="';
                                 echo $row['year'].'">';
                                 echo $row['year'];
                                 echo '</option>';
                                 }
                                 ?>
                           </select>
                           </h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                           <div class="chart-pie pt-4">
                              <canvas id="pieChart"></canvas>
                           </div>
                           <hr>
                        </div>
                     </div>
                     <!-- Earnings (Monthly) Card Example -->
                     <!-- Area Chart -->
                     <div class="container card text-center shadow mb-4 p-0 m-0">
                        <div class="card-header text-center">
                           <h5 class="m-1 font-weight-bold text-primary">
                              הכנסות לפי שנה  
                           </h5>
                           <select class="custom-select" id="chosenYear">
                           <?php 
                              $years->data_seek(0);
                              while($row = mysqli_fetch_assoc($years)) {
                                 echo '<option value="';
                                 echo $row['year'].'">';
                                 echo $row['year'];
                                 echo '</option>';
                                 }
                                 ?>
                           </select>
                           </h6>
                        </div>
                        <div class="card-body p-0">
                           <div class="chart-area">
                              <canvas id="myAreaChart"></canvas>
                           </div>
                           <hr>
                           <div class="h6 text-xs font-weight-bold text-success mb-1">  סך הכנסות שנתיות:  
                              <span id="yearlyIncome" class="h6 mb-0 font-weight-bold text-gray-800"></span>
                           </div>
                        </div>
                     </div>
                     <!-- Bar Chart -->
                     <div class="container card shadow mb-4 p-0 text-center">
                        <div class="card-header">
                           <h5 class="m-1 font-weight-bold text-primary">הכנסות חודשיות לפי ימים </h5>
                           <select class="custom-select" id="chosenYear2">
                           <?php 
                              $years->data_seek(0);
                              while($row = mysqli_fetch_assoc($years)) {
                                 echo '<option value="';
                                 echo $row['year'].'">';
                                 echo $row['year'];
                                 echo '</option>';
                                 }
                                 ?>
                           </select>
                           <select class="custom-select" id="chosenMonth">
                           <?php 
                              $months = array();
                              $months =  ["ינואר", "פברואר", "מרץ", "אפריל", "מאי", "יוני", "יולי", "אוגוסט", "ספטמבר", "אוקטובר", "נובמבר", "דצמבר"];
                              while($row = mysqli_fetch_assoc($month)) {
                                 echo '<option value="';
                                 echo $row['month'].'">';
                                 echo $months[$row['month']-1];
                                 echo '</option>';
                                 }
                                 ?>
                           </select>
                           <button class="btn btn-primary" id="monthYear">שגר אותי</button>
                           </h6>
                        </div>
                        <div class="card-body">
                           <div class="chart-month">
                              <canvas id="myMonthChart"></canvas>
                           </div>
                           <hr>
                           <div id="choosenMonth"class="h6 text-xs font-weight-bold text-success mb-1">  סך ההכנסות לחודש הנבחר:  
                              <span id="choosenMonthIncome" class="h6 mb-0 font-weight-bold text-gray-800"></span>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
         </div>
         <!-- End of Content Wrapper -->
      </div>
      <!-- End of Page Wrapper -->
      <?php include "../sidebar/sidebarF.php"; ?>
      <!-- JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <!-- Page level custom scripts -->
      <script src="script.js"></script>
   </body>
</html>