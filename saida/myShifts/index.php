<?php  
   session_start();
   require_once ('../includes/init.php');
   $user = new User();
   $uid = $_SESSION['uid'];
   $role = $_SESSION['role'];
   if (!$user->get_session()){
   header("location:../login.php");
   exit();
   }
   if (isset($_GET['q'])){
   $user->user_logout();
   header("location:../login.php");
   exit();
   }
   $query = "SELECT YEAR(date) AS year FROM all_shifts WHERE uid = '$uid' GROUP BY YEAR(date) ORDER BY date DESC";
   $years = $database->query($query);
   $query = "SELECT MONTH(date) AS month FROM all_shifts WHERE uid = '$uid' GROUP BY MONTH(date) ORDER BY date DESC ";
   $month = $database->query($query);
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
      <link rel="stylesheet" href="index.css">
      <title>המשמרות שלי</title>
   </head>
   <body>
      <?php include "../sidebar/sidebarH.php"; ?>
      <div class="col-md-4">
         <button type="button" class="btn btn-primary mb-2 mt-4 shadow" id="addGift" data-toggle="modal" data-target="#addModal">הגשת משמרות</button>
      </div>
      <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <h5 class="modal-title text-center" id="addModalLabel">הגשת משמרות</h5>
                  <hr>
                  <div class="text-center container">
                     <form method="POST">
                        <input type="hidden" value="<?php echo $uid; ?>;" id="uid">
                        <div class="checkbox-group required">
                           <div class="custom-control custom-checkbox">
                              <input class="custom-control-input" type="checkbox" value="checked" id="sunday" name="sunday">
                              <label class="custom-control-label" for="sunday">
                              יום ראשון
                              </label>
                           </div>
                           <div class="custom-control custom-checkbox">
                              <input class="custom-control-input" type="checkbox" value="checked" id="monday" name="monday">
                              <label class="custom-control-label" for="monday">
                              יום שני
                              </label>
                           </div>
                           <div class="custom-control custom-checkbox">
                              <input class="custom-control-input" type="checkbox" value="checked" id="tuesday" name="tuesday">
                              <label class="custom-control-label" for="tuesday">
                              יום שלישי
                              </label>
                           </div>
                           <div class="custom-control custom-checkbox">
                              <input class="custom-control-input" type="checkbox" value="checked" id="wednesday" name="wednesday">
                              <label class="custom-control-label" for="wednesday">
                              יום רביעי
                              </label>
                           </div>
                           <div class="custom-control custom-checkbox">
                              <input class="custom-control-input" type="checkbox" value="checked" id="thursday" name="thursday">
                              <label class="custom-control-label" for="thursday">
                              יום חמישי
                              </label>
                           </div>
                           <div class="custom-control custom-checkbox">
                              <input class="custom-control-input" type="checkbox" value="checked" id="friday" name="friday">
                              <label class="custom-control-label" for="friday">
                              יום שישי
                              </label>
                           </div>
                           <div class="custom-control custom-checkbox">
                              <input class="custom-control-input" type="checkbox" value="checked" id="saturday" name="saturday">
                              <label class="custom-control-label" for="saturday">
                              יום שבת
                              </label>
                           </div>
                        </div>
                        <button type="button" name="submit" id="submit" class="btn btn-primary mt-3 shadow">שלח</button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="mt-4 container">
         <div class="row">
            <div class="col">
               <select class="custom-select shadow" id="chosenYear">
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
               <select class="custom-select shadow" id="chosenMonth">
               <?php 
                  $months = array();
                  $months =  ["ינואר", "פברואר", "מרץ", "אפריל", "מאי", "יוני", "יולי", "אוגוסט", "ספטמבר", "אוקטובר", "נובמבר", "דצמבר"];
                  while($row = mysqli_fetch_assoc($month)) {
                     echo '<option  value="';
                     echo $row['month'].'">';
                     echo $months[$row['month']-1];
                     echo '</option>';
                     }
                     ?>
               </select>
            </div>
            <div class="col">
               <button class="btn btn-light text-primary m-0 shadow" id="showMyShifts">הצג משמרות</button>
            </div>
         </div>
      </div>
      <div class="container" id="myShifts">
      </div>
      <?php include "../sidebar/sidebarF.php"; ?>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script src="script.js"></script>
      <script>
function createCards(month, year) {
	$('#myShifts').empty();
	$.ajax({
		type: "GET",
		url: "../includes/myShifts.php",
		data: {
			year: year,
			month: month
		},
		success: function (data2) {
			var data = JSON.parse(data2);
			if (data != 0) {
				var count = 1;
				var row = $('<div class = "row">');
				for (var x = 0; x < data.length; x++, count++) {
					var col = $('<div class="col-md-4">');
					var center = $('<div class="mt-3 text-center">');
					var accordion = $('<div id="accordion" role="tablist" aria-multiselectable="true">');
					var card = $('<div class="card m-1 p-0 shadow">');
					var h5 = $('<h5 class="card-header" role="tab" id="heading' + count + '">');
					var a = $('<a data-toggle="collapse" data-parent="#accordion" href="#collapse' + count + '" aria-expanded="true" aria-controls="collapse' + count + '" class="d-block text-center text-primary">');
					var i = $('<i class="fa fa-chevron-down pull-right text-left"></i>');
					var cardbody1 = $('<div id="collapse' + count + '" class="collapse" role="tabpanel" aria-labelledby="heading' + count + '">');
					var cardbody2 = $('<div class="card-body">');
					var div = $('<div></div>');
					var start = new Date(data[x].start).toLocaleTimeString("en-GB");
					var end = new Date(data[x].end).toLocaleTimeString("en-GB");
					var li1 = $('<li>משעה: ' + start + ' עד שעה: ' + end + '</li>');
					var li2 = $('<li><b>סה"כ: </b>' + data[x].total + ' <i class="far fa-clock"></i></li>');
					var tips_hour = 0;
					$.ajax({
						type: "GET",
						url: "../includes/myShifts.php",
						data: {
							date: data[x].date
						},
						dataType: "JSON",
						success: function (data1) {
							var tips = data1;
							localStorage.setItem('tips', tips);
						}
					});
					tips_hour = localStorage.getItem('tips');
					var role = '<?php echo $role; ?>';
					div.append(li1);
					div.append(li2);
					if (role == 'מלצר' || role == 'ברמן' || role == 'אחמ"ש') {
					    var li3 = $('<li><b>טיפ לשעה: </b>₪ ' + tips_hour + ' <i class="fas fa-coins"></i></li>');
					    var li4 = $('<li><b>סה"כ: </b>₪ ' + (tips_hour * data[x].totald) + ' <i class="fas fa-coins"></i></li>');
						div.append(li3);
						div.append(li4);
					}
					cardbody2.append(div);
					cardbody1.append(cardbody2);
					a.append(i);
					var days = ["ראשון", "שני", "שלישי", "רביעי", "חמישי", "שישי", "שבת"];
					var date = new Date(data[x].date);
					a.append(' ' + days[date.getDay()] + ', ' + date.toLocaleDateString("en-GB"));
					h5.append(a);
					card.append(h5);
					card.append(cardbody1);
					accordion.append(card);
					center.append(accordion);
					col.append(center);
					row.append(col);
					$('#myShifts').append(row);
				}
			} else {
				swal({
					title: "יא עצלן, עדיין לא עבדת בחודש הנוכחי",
					text: "בחייאת תשנה את השנה/החודש",
					icon: "info",
					button: "סבבה",
				});
			}
		}
	});
}
      </script>
   </body>
</html>