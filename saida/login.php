    <?php  
   session_start();
   ?>
<!DOCTYPE html>
<html>
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
      <!-- Bootstrap CSS -->
      <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/minty/bootstrap.min.css" rel="stylesheet" integrity="sha384-9NlqO4dP5KfioUGS568UFwM3lbWf3Uj3Qb7FBHuIuhLoDp3ZgAqPE1/MYLEBPZYM" crossorigin="anonymous">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
      <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
      <link rel="stylesheet" href="css/floating-labels.css">
      <title>סעידה בפארק - התחברות</title>
   </head>
   <body>
      <br>
      <div class="container">
         <div class="row text-center">
            <div class="col-sm"></div>
            <div class="col-sm"><img src="sidebar/img/logo-login.png"></div>
            <div class="col-sm"></div>
         </div>
      </div>
      <div class="container">
         <div class="row text-center">
            <div class="col-sm"></div>
            <div class="col-sm">
               <form dir="rtl" action="includes/check_login.php" method="POST" class="form-signin" name="login">
                  <div class="form-label-group">
                     <input type="email" name="email" id="email" placeholder="שם משתמש/אימייל" class="form-control" required autofocus>
                     <label for="email">שם משתמש/אימייל</label>
                  </div>
                  <div class="form-label-group">
                     <input type="password" name="uid" id="uid" placeholder="תעודת זהות" class="form-control" required>
                     <label for="uid">תעודת זהות</label>
                  </div>
                  <div class="form-group text-center">
                     <input class="btn btn-lg btn-primary shadow" type="submit" name="login" value="התחבר"/>
                  </div>
                  <?php 
                     // Show any success or error message 
                     	if(isset($_SESSION['msg'])) {
                     		echo $_SESSION['msg'];
                     		session_unset($_SESSION['msg']);
                     	}
                     ?>
               </form>
            </div>
            <div class="col-sm"></div>
         </div>
      </div>
      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script
         src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
         integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
         crossorigin="anonymous"
         ></script>
      <script
         src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
         integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
         crossorigin="anonymous"
         ></script>
      <script
         src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
         integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
         crossorigin="anonymous"
         ></script>
   </body>
</html>