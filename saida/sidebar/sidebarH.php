<nav class="navbar navbar-expand navbar-dark bg-primary fixed-top">
   <a href="#menu-toggle" id="menu-toggle" class="navbar-brand"><i id="arrow" class="fas fa-chevron-right"></i></a>
   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample02" aria-controls="navbarsExample02" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
   <div class="collapse navbar-collapse" id="navbarsExample02">
       <?php echo '<span class="h5 mr-4 text-light"> שלום, '.$_SESSION['fname'].' '.$_SESSION['lname'].'</span>'?>
      <ul class="navbar-nav mr-auto">
          
         <?php 
            if($_SESSION['role'] == 'מלצר' || $_SESSION['role'] == 'ברמן' || $_SESSION['role'] == 'מארחת' || $_SESSION['role'] == 'טבח'){
                echo '<a class="text-secondary" id="heart" href="../wishlist/"><i id="heart" class="fas fa-heart fa-2x "></i></a>';
            }
            ?>
         <a class="btn btn-light text-primary mr-3" href="../index.php?q=logout">התנתק</a>
      </ul>
   </div>
</nav>
<div id="wrapper">
<!-- Sidebar -->
<div id="sidebar-wrapper" class="bg-primary">
   <ul class="sidebar-nav">
      <div id="side-brand-wrraper" class="sidebar-brand ">
          
         <?php
            $sql = "SELECT image FROM users WHERE uid='$uid'";
            $result = $database->query($sql);
            $row = $result->fetch_assoc();
            $img = '../sidebar/img/'.$row["image"];
            ?>
         <div id="image" class="round-pic thumbnail mt-3" style="background-image: url('<?php echo $img; ?>');">
            <span><i id="edit" class="fas fa-2x fa-user-edit text-white"></i></span>
         </div>
         <form id="editProfilePic" action="../includes/avatar.php" method="post" enctype="multipart/form-data">
            <input id="profile" type="file" name="image">
         </form>
      </div>
     
      <?php
         if($_SESSION['role'] == 'מלצר' || $_SESSION['role'] == 'ברמן' || $_SESSION['role'] == 'מארחת' || $_SESSION['role'] == 'טבח'){    
              echo '<div class="mt-1 text-light w-100 text-center shadow" id="inshift"></div>';
              echo "<li><a href='../myShifts/'>המשמרות שלי</a></li>";
              echo "<li><a href='../calendar/'>סידור עבודה</a></li>";
              echo "<li><a href='../gifts/'>מאגר מתנות</a></li>";
              echo "<li><a href='../leaderBoard/'>טבלת מובילים</a></li>";
              
            }
            if($_SESSION['role']=='מנהל'){    
              echo "<li> <a href='../stats/'>סטטיסטיקות</a></li>";    
              echo "<li> <a href='../reports/'> דוחות סיכום</a></li>";
              echo "<li> <a href='../employees/'>מאגר עובדים</a></li>";
              echo "<li> <a href='../calendar/'>סידור עבודה</a></li>";
              echo "<li> <a href='../shifts/'>מאגר משמרות</a></li>";
              echo "<li> <a href='../gifts/'>מאגר מתנות</a></li>";
              echo "<li><a href='../leaderBoard/'>טבלת מובילים</a></li>";
            }
            if($_SESSION['role']=='אחמ"ש'){    
              echo '<div class="mt-1 text-light w-100 text-center" id="inshift"></div>';
              echo "<li><a href='../myShifts/'>המשמרות שלי</li>";
              echo "<li> <a href='../reports/'> דוחות סיכום</a></li>";
              echo "<li> <a href='../calendar/'>סידור עבודה</a></li>";
              echo "<li> <a href='../shifts/'>מאגר משמרות</a></li>";
              echo "<li><a href='../leaderBoard/'>טבלת מובילים</a></li>";
            }
            ?>
   </ul>
</div>
<!-- /#sidebar-wrapper -->
<!-- Page Content -->
<div id="page-content-wrapper">
<div class="mt-5">