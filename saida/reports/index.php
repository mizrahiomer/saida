<?php  
   session_start();
   date_default_timezone_set('Asia/Jerusalem');
   require_once ('../includes/init.php');
   $user = new User();
   $uid = $_SESSION['uid'];
   if (!$user->get_session()){
   header("location:../login.php");
   exit();
   }
   if ($_SESSION['role'] != 'מנהל' && $_SESSION['role'] != 'אחמ"ש'){
       header("location: ../index.php");
       exit();
   }
   if (isset($_GET['q'])){
   $user->user_logout();
   header("location:../login.php");
   exit();
   }
   $query = "SELECT YEAR(date) AS year FROM shifts_summaries GROUP BY YEAR(date) ORDER BY date DESC";
      $years = $database->query($query);
      $query = "SELECT MONTH(date) AS month FROM shifts_summaries GROUP BY MONTH(date)";
      $month = $database->query($query);
      $yesterday = date("Y-m-d", strtotime('-1 days'));
      $achmash = 'אחמ"ש';
      $query = "SELECT * FROM all_shifts WHERE date='$yesterday' AND role != '$achmash'";
      $emps = $database->query($query);
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
      <link rel="stylesheet" href="../sidebar/sidebar.css">
      <link rel="stylesheet" href="../css/floating-labels.css">
      <link rel="stylesheet" href="index.css">
      <title>דוחות סיכום משמרת</title>
   </head>
   <body>
      <?php include "../sidebar/sidebarH.php"; ?>
      <div class="container">
         <div class="row">
            <div class="col-md-4">
               <i id="add" class="fas fa-plus fa-2x btn btn-primary" data-toggle="modal" data-target="#addReport"></i>
               <!-- Modal -->
               <div class="modal fade" id="addReport" tabindex="-1" role="dialog" aria-labelledby="addReportCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document" dir="rtl">
                     <div class="modal-content">
                        <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                        <div class="modal-body">
                           <div class="row no-gutters align-items-center">
                              <div class="col">
                                 <form id="report" method="POST" dir="rtl" class="text-right" name="report">
                                    <h1 class="text-center">דוח סיכום משמרת</h1>
                                    <hr />
                                    <div class="row">
                                       <div class="col">
                                          <div class="form-label-group text-center">
                                             <input class="form-control datedropper-init" name="date" id="date" data-dd-theme="date" data-dd-large="true" data-dd-jump="5" data-dd-lang="he" data-dd-start-from-monday="false"  type="text" required />
                                             <label for="date">תאריך</label>
                                          </div>
                                       </div>
                                       <div class="col">
                                          <div class="form-label-group text-center">
                                             <input class="form-control" type="tel" name="total" id="total" pattern="^[0-9]*$" oninvalid="this.setCustomValidity('רק מספרים')" placeholder='סה"כ כללי' required />
                                             <label for="total">סה"כ כללי</label>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col">
                                          <div class="form-label-group text-center">
                                             <input class="form-control" type="tel" name="cash" id="cash" pattern="^[0-9]*$" oninvalid="this.setCustomValidity('רק מספרים')" placeholder='סה"כ מזומן' required />
                                             <label for="cash">סה"כ מזומן</label>
                                          </div>
                                       </div>
                                       <div class="col">
                                          <div class="form-label-group text-center">
                                             <input class="form-control" type="tel" name="credit" id="credit" pattern="^[0-9]*$" oninvalid="this.setCustomValidity('רק מספרים')" placeholder='סה"כ אשראי' required />
                                             <label for="credit">סה"כ אשראי</label>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col">
                                          <div class="form-label-group text-center">
                                             <input class="form-control" type="tel" name="customers" id="customers" pattern="^[0-9]*$" oninvalid="this.setCustomValidity('רק מספרים')" placeholder='סה"כ לקוחות' required />
                                             <label for="customers">סה"כ לקוחות</label>
                                          </div>
                                       </div>
                                       <div class="col">
                                          <div class="form-label-group text-center">
                                             <input class="form-control" type="tel" name="cancellations" id="cancellations" pattern="^[0-9]*$" oninvalid="this.setCustomValidity('רק מספרים')" placeholder='סה"כ ביטולים' required />
                                             <label for="cancellations">סה"כ ביטולים</label>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col">
                                          <div class="form-label-group text-center">
                                             <input class="form-control" type="tel" name="discounts" id="discounts" pattern="^[0-9]*$" oninvalid="this.setCustomValidity('רק מספרים')" placeholder='סה"כ הנחות' required />
                                             <label for="discounts">סה"כ הנחות</label>
                                          </div>
                                       </div>
                                       <div class="col">
                                          <div class="form-label-group text-center">
                                             <input class="form-control" type="tel" name="tips" id="tips" pattern="^[0-9]*$" placeholder='סה"כ טיפים' oninvalid="this.setCustomValidity('רק מספרים')" required />
                                             <label for="tips">סה"כ טיפים</label>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="form-label-group">
                                       <textarea rows="4" cols="50" class="form-control" type="tel" name="summary" id="summary" placeholder='סיכום' oninvalid="this.setCustomValidity('אם אין, רשום אין')" required></textarea>
                                    </div>
                                    <hr>
                                    <h3 class="text-center">עובד מצטיין</h3>
                                    <select class="custom-select" id="exceptional">
                                    <?php
                                       while($row = mysqli_fetch_assoc($emps)){
                                           echo '<option value="';
                                           echo $row['uid'].'">';
                                           echo $row['fname'].' '.$row['lname'];
                                           echo '</option>';
                                       }
                                       ?>
                                    </select>
                                    <div class="mx-auto text-center">
                                       <button type="button" class="btn btn-primary mt-2" id="pinuk" name="pinuk">פנק ב-50 נקודות</button>
                                    </div>
                                    <input type="hidden" id="uid" value="<?php echo $uid; ?>">
                                 </form>
                              </div>
                           </div>
                        </div>
                        <div class="modal-footer ">
                           <div class="mx-auto">
                              <button type="button" class="btn btn-primary " id="submit" name="submit">שלח</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="mt-2 container">
               <div class="row">
                  <div class="col">
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
                  </div>
                  <div class="col">
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
                  </div>
                  <div class="col">
                     <button class="btn btn-light text-primary" id="showReports">הצג דוחות</button>
                  </div>
               </div>
            </div>
            <div class="container" id="reports">
            </div>
         </div>
      </div>
      <?php include "../sidebar/sidebarF.php"; ?>
      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="https://cdn.datedropper.com/get/h7vy9qfdy689to3oux03bi3h0mmfqtxw"></script>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script src="../js/datedropper-HE.js"></script>
      <script src="script.js"></script>
   </body>
</html>