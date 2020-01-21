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
   if($role == 'מנהל'){
       header("location: ../index.php");
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
      <title>המתנות שלי</title>
   </head>
   <body>
      <?php include "../sidebar/sidebarH.php";?>
      <div id="myScore" class="h4"></div>
      <div class="row">
         <?php
            $sql = "SELECT * FROM gifts WHERE id IN (SELECT gid FROM wishlist WHERE uid = $uid AND purchased=false)";
            $result = $database->query($sql);
            $count=0;
             if ($result->num_rows == 0) {
                echo '<p class="mr-3">להוספת מתנה לחץ על ה <i class="fas fa-heart fa-2x"></i> במאגר המתנות</p>';
            } else {
            while($row = mysqli_fetch_assoc($result)) {?>
         <div class="col-lg-4 col-md-6 my-3">
            <!-- Card -->
            <div class="card <?php if ( $row['amount'] == 0) echo 'disabledCard';?> shadow">
               <!-- Card image -->
               <div class="view overlay">
                  <img class="card-img-top" src="../gifts/img/<?php echo $row['image'];?>">
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
                  <p> מחיר : <?php echo $row['price']; ?> נק'</p>
                  <!-- Button -->
                  <div class="row">
                     <button id="<?php echo $row['id']; ?>"class="btn btn-light text-secondary m-1" onclick="deleteFromWishlist(this.id)"><i id="heart" class="fas fa-heart fa-2x"></i></button>
                     <button id="b<?php echo $row['id']; ?>" class="btn btn-light <?php if ((($score / $row['price'])*100) < 100) echo 'disabled text-muted'; else echo 'text-success'; ?> m-1" onclick="buyGift(this.id)"><i id="buy" class="fas fa-shekel-sign fa-2x "></i></button>
                     <?php if ( $row['amount'] == 0 && $role!="מנהל") echo '<h4 class="mx-auto mt-3 text-danger">המוצר אזל במלאי</h4>'; else{?>
                     <span class="ldBar label-center" style="width:100%;height:70%;margin:auto" data-precision="1" data-preset="energy" data-fill-background="#d9d9d9" data-type="fill" data-value="<?php echo ($score / $row['price'])*100; ?>"></span>
                     <?php } ?>
                  </div>
               </div>
            </div>
            <!-- Card -->
         </div>
         <?php
            }
            }
            $count++; 
            ?>
      </div>
      <?php include "../sidebar/sidebarF.php";?>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script src="../js/loading-bar.js"></script>
      <script src="script.js"></script>
   </body>
</html>