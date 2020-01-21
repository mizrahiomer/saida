<?php  
   session_start();
   require_once ('../includes/init.php');
   $user = new User();
   $uid = $_SESSION['uid'];
   $role = $_SESSION['role'];
   $score = $_SESSION['score'];
   if (!$user->get_session()){
   header("location:../login.php");
   exit();
   }
   if (isset($_GET['q'])){
   $user->user_logout();
   header("location:../login.php");
   exit();
   }
   ?>
<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!-- Bootstrap CSS -->
      <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/minty/bootstrap.min.css" rel="stylesheet" integrity="sha384-9NlqO4dP5KfioUGS568UFwM3lbWf3Uj3Qb7FBHuIuhLoDp3ZgAqPE1/MYLEBPZYM" crossorigin="anonymous" />
      <!-- FontAwesome CSS-->
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous" />
      <link rel="stylesheet" href="../sidebar/sidebar.css" />
      <link rel="stylesheet" href="../css/loading-bar.css"/>
      <link rel="stylesheet" href="index.css"/>
      <title>המתנות של סעידה</title>
   </head>
   <body>
      <?php include "../sidebar/sidebarH.php";?>
      <div id="myScore" class="h4"></div>
      <button type="button" class="btn btn-primary mt-0 shadow" id="addGift" data-toggle="modal" data-target="#addModal">הוסף מתנה</button>
      </div>
      <!-- add -->
      <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="addModalLabel"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <form id="myform" action="../includes/manageGifts.php" method="post" enctype="multipart/form-data">
                     <div class="form-group">
                        <input type="text" class="form-control text-center" placeholder="שם המתנה" name="name" id="name" required>
                     </div>
                     <div class="form-group">
                        <input type="tel" class="form-control text-center" placeholder="שווי בנקודות" name="price" id="price" required>
                     </div>
                     <div class="form-group">
                        <input type="tel" class="form-control text-center" placeholder="כמות" name="amount" id="amount" required>
                     </div>
                     <div class="form-group">
                        <div class="input-group">
                           <div class="custom-file">
                              <input type="file" class="custom-file-input" id="image" name="image" required>
                              <label class="custom-file-label text-center" id="image-label" for="image">תמונה</label>
                           </div>
                        </div>
                     </div>
                     <div dir="rtl">
                        <div class="form-group">
                           <textarea type="text" class="form-control text-center" placeholder="תיאור המתנה" name="description" id="description"></textarea>
                        </div>
                     </div>
                     <div class="text-center">
                        <input type="submit" class="btn btn-primary" name="submitimg" value="הוסף מתנה">
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!--edit-->
      <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="editModalLabel"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <form id="myform" action="../includes/manageGifts.php" method="POST" enctype="multipart/form-data">
                     <input type = "hidden" id="gid" name="gid">
                     <div class="form-group">
                        <input type="text" class="form-control text-center" placeholder="שם המתנה" name="name" id="editName" required>
                     </div>
                     <div class="form-group">
                        <input type="tel" class="form-control text-center" placeholder="שווי בנקודות" name="price" id="editPrice" required>
                     </div>
                     <div class="form-group">
                        <input type="tel" class="form-control text-center" placeholder="כמות" name="amount" id="editAmount" required>
                     </div>
                     <div class="form-group">
                        <div class="input-group">
                           <div class="custom-file">
                              <input type="file" class="custom-file-input" id="editImage" name="image">
                              <label class="custom-file-label text-center" id="edit-image-label" for="editImage">תמונה</label>
                           </div>
                        </div>
                     </div>
                     <div dir="rtl">
                        <div class="form-group">
                           <textarea type="text" class="form-control text-center" placeholder="תיאור המתנה" name="description" id="editDescription"></textarea>
                        </div>
                     </div>
                     <div class="text-center">
                        <input type="submit" class="btn btn-primary" name="submitimg" value="ערוך מתנה">
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- view -->
      <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header display-flex">
                  <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <h3 class="modal-title text-center" id="viewModalLabel">עובדים שרכשו את המתנה</h3>
               <div id="modal-body-content">
                  <div class="modal-body" id="content">
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <?php
            $sql = "SELECT * FROM gifts";
            $result = $database->query($sql);
            $count=0;
            while($row = mysqli_fetch_assoc($result)) {?>
         <div class="col-lg-4 col-md-6 my-3">
            <!-- Card -->
            <div class="card <?php if ( $row['amount'] == 0 && $role!="מנהל") echo 'disabledCard';?> shadow">
               <!-- Card image -->
               <div class="view overlay">
                  <img class="card-img-top" src="img/<?php echo $row['image'];?>">
                  <a href="#!">
                     <div class="mask rgba-white-slight"></div>
                  </a>
               </div>
               <!-- Card content -->
               <div class="card-body">
                  <!-- Title -->
                  <h4 class="card-title"><?php echo $row['name']; ?></h4>
                  <!-- Text -->
                  <p class="card-text"><?php echo $row['description']; ?></p>
                  <?php if($role == 'מנהל'){?>
                  <p <?php if($row['amount']==0) echo 'class="text-danger font-weight-bold"';?>> כמות : <?php echo $row['amount']; ?> יחידות</p>
                  <?php
                     }
                     ?>
                  <p> מחיר : <?php echo $row['price']; ?> נק'</p>
                  <!-- Button -->
                  <div class="row ">
                     <?php if($role=="מנהל"){?>
                     <button id="v<?php echo $row['id']; ?>"class="btn btn-light mx-1"  onclick="viewGift(this.id)"><i id="view" class="far fa-eye fa-2x text-info"></i><span class="badge badge-secondary"></span></button>
                     <button id="e<?php echo $row['id']; ?>"class="btn btn-light mx-1" data-toggle="modal" data-target="#editModal"onclick="editGift(this.id)"><i id="edit" class="fas fa-edit fa-2x text-warning"></i></button>
                     <button id="d<?php echo $row['id']; ?>"class="btn btn-light mx-1" onclick="deleteGift(this.id)"><i id="delete"class="fas fa-trash-alt fa-2x text-danger"></i></button>
                     <?php } else {?>
                     <button id="h<?php echo $row['id']; ?>"class="btn btn-light" onclick="wishlist(this.id)"><i id="heart" class="fas fa-heart fa-2x m-1"></i></button>
                     <button id="b<?php echo $row['id']; ?>" class="btn btn-light <?php if ((($score / $row['price'])*100) < 100) echo 'disabled text-muted'; else echo 'text-success'; ?> m-1" onclick="buyGift(this.id)"><i id="buy" class="fas fa-shekel-sign fa-2x "></i></button>
                     <?php if ( $row['amount'] == 0 && $role!="מנהל") echo '<h4 class="mx-auto mt-3 text-danger">המוצר אזל במלאי</h4>'; else{?>
                     <span class="ldBar label-center" style="width:100%;height:70%;margin:auto" data-precision="1" data-preset="energy" data-fill-background="#d9d9d9" data-type="fill" data-value="<?php echo ($score / $row['price'])*100; ?>"></span>
                     <?php } ?>
                     <?php } ?>
                  </div>
               </div>
            </div>
            <!-- Card -->
         </div>
         <?php
            }
            $count++; 
            ?>
      </div>
      <?php include "../sidebar/sidebarF.php";?>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script src="script.js"></script>
      <script src="../js/loading-bar.js"></script>
      <script>
         $(document).ready(function () {
         getScore();
         var role = "<?php echo $role ; ?>";
         if (role != "מנהל") {
         $('#addGift').hide();
         }
         if (role == "מנהל") {
         $('#myScore').hide();
         }
         if (role != "מנהל") {
         $.ajax({
         type: "PUT",
         url: "../includes/wishlist.php",
         datatype: "JSON",
         success: function (data) {
         var data = JSON.parse(data);
         for (i = 0; i < data.length; i++) {
         $("#h" + data[i].gid).toggleClass('text-secondary');
         }
         }
         });
         }
         });
      </script>
   </body>
</html>