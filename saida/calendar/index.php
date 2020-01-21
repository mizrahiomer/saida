<?php
   //index.php
   session_start();
   require_once('../includes/init.php');
   $user = new User();
   $role = $_SESSION['role'];
   if (!$user->get_session()) {
    header("location:../login.php");
    exit();
    }
    if (isset($_GET['q'])) {
        $user->user_logout();
        header("location:../login.php");
        exit();
    }
   ?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=0.5, shrink-to-fit=no">
      <title>המשמרות של סעידה</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.css" />
      <link rel="stylesheet" href="https://bootswatch.com/4/minty/bootstrap.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href="showCalendar.css"/>
      <style>
         .swal-text{
         text-align: center;
         font-size: 2rem;
         }
         .swal-footer{
         text-align: center;
         }
      </style>
   </head>
   <body>
      <div class="container-fluid" >
      <div dir="rtl" class="row">
         <button class="btn btn-primary m-3" name="back" id="back" onclick="goBack()" >חזרה לעמוד הבית</button>
      </div>
      <div class="row">
         <div class="col-9"></div>
         <div class= "mt-5 ml-5 col-2">
            <select dir="rtl" id="role" class="custom-select" onchange="createShiftsBoard()">
               <option value="" selected disabled>בחר תפקיד</option>
               <?php
                  $roles = "SELECT * FROM requests GROUP BY role";
                  $result = $database->query($roles);
                  while($row = mysqli_fetch_assoc($result)) {
                      echo '<option class="text-primary" value='."'";
                      echo $row['role']."'".'>';
                      echo $row['role'];
                      echo '</option>';
                  }
                  ?>
            </select>
         </div>
      </div>
      <div class="row">
         <div id="calendar" class="col-9 mx-auto"></div>
         <div id="shifts" class="col-2 mr-4"></div>
      </div>
      `
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
      <script src="createShiftsBoard.js" type="text/javascript"></script>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js"></script>
      <script src="he.js"></script>
      <script>
         $(document).ready(function () {
         var loader = '<?php if($_GET["view"]=="mine"){ echo "myLoad.php"; } else { echo "load.php"; }?>';
         var role = '<?php if($role!="מנהל"){ echo false; } else { echo true; }?>';
         var flag = 'send';
         if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                 if(role){
                     swal({
                        title: "אהלן וסהלן",
                        text: "סמוך עלינו, יותר נוח ליצור סידור עבודה במחשב. במובייל צופים בלבד",
                        icon: "warning",
                        button: "סבבה, הבנתי",
                        dangerMode: true
                     });
                     flag = 'allShifts,myShifts';
               $('#shifts').hide();
               $('#role').hide();
               $('#note').hide();
               $('#calendar').removeClass('col-9');
               $('#calendar').addClass('container-fluid');
              
                 }
             }
         if (!role) {
          flag = 'allShifts,myShifts';
         $('#shifts').hide();
         $('#role').hide();
         $('#note').hide();
         $('#calendar').removeClass('col-9');
         $('#calendar').addClass('container-fluid');
         }
         var calendar = $('#calendar').fullCalendar({
         editable: role,
         droppable: role,
         height: 500,
         displayEventEnd:false,
             displayEventTime:false,
          slotEventOverlap : false,
          eventOverlap : false,
          slotDuration : "00:10:00",
          defaultTimedEventDuration: "00:10:00",
          slotLabelFormat : "HH:mm",
         allDaySlot: false,
         titleFormat: 'MMMM DD YYYY',
         drop: function (date, jsEvent) {
         var start = date.format();
         var end = date.format();
         var title = jsEvent.target.childNodes[0].data;
         var uid = jsEvent.target.firstElementChild.innerText;
         var backgroundColor = jsEvent.target.classList[0];
         $.ajax({
         url: "insert.php",
         type: "POST",
         data: {
         	title: title,
         	start: start,
         	end: end,
         	backgroundColor: backgroundColor,
         	uid: uid
         },
         success: function () {
         	calendar.fullCalendar('refetchEvents');
         }
         })
         if ($('#drop-remove').is(':checked')) {
         // if so, remove the element from the "Draggable Events" list
         $(this).remove();
         }
         },
         locale: 'he',
         isRTL: true,
         buttonText: {
         today: 'היום',
         month: 'חודשית',
         week: 'שבועית',
         day: 'יומית',
         list: 'רשימה'
         },
         header: {
         left: flag,
         center: 'title',
         right: 'prev,next agendaDay,agendaWeek'
         },
         forceEventDuration: true,
         navLinks: true,
         minTime: "17:00",
         maxTime : "22:30",
         customButtons: {
         myShifts: {
         text: "המשמרות שלי",
         click: function () {
         	window.location.href = "index.php?view=mine";
         }
         },
         allShifts: {
         text: "כל המשמרות",
         click: function () {
         	window.location.href = "../calendar";
         }
         },
         send: {
         text: "שלח סידור עבודה",
         click: function () {
             $.ajax({
                 type: "POST",
                 url: "publishCalendar.php",
                 success: function(data){
                     if (data == 1){
                         swal({
                                        title : "סידור נשלח בהצלחה",
                                        icon : 'success',
                                        button : "לחזרה לדף הבית",
                                     }).then(function(){
                                         window.location.href = '../';
                                     });
                     }
                               }
                 });
         }
         }
         
         },
         defaultView: "agendaWeek",
         themeSystem: "bootstrap4",
         eventBorderColor: "black",
         nowIndicator: true,
         events: loader,
         selectable: role,
         selectHelper: role,
         eventDurationEditable : false,
         eventDrop: function (event) {
         var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
         var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
         var title = event.title;
         var id = event.id;
         $.ajax({
         url: "update.php",
         type: "POST",
         data: {
         	title: title,
         	start: start,
         	end: end,
         	id: id
         },
         success: function () {
         	calendar.fullCalendar('refetchEvents');
         	$("customTooltip").hide();
         }
         });
         },
         eventClick: function (event) {
             console.log(event);
             var userRole = '<?php echo $role; ?>';
             if(userRole == 'מנהל'){
                 swal({
                    title: "?האם אתה בטוח שברצונך למחוק" ,
                    dangerMode: true,
                    buttons: ["ביטול", "כן"],
                    icon: "warning"
                 }).then((willDelete) => {
                   if (willDelete) {
                       var id = event.id;
                         $.ajax({
                         	url: "delete.php",
                         	type: "POST",
                         	data: {
                         		id: id
                         	},
                         	success: function () {
                         		calendar.fullCalendar('refetchEvents');
                         
                         	}
                         });
                         swal({
                         title: "נמחק בהצלחה",
                         icon: "success",
                         buttons: 'סבבה'
                     });
                   } else {
                     swal({
                         text: "הכל טוב, לא מחקנו כלום :)",
                         buttons: "סבבה",
                         icon: "info"
                   });
                 };
              });;
             }
         },
         });
         });
      </script>
      <script>
         function goBack() {
           window.location.href= "../index.php";
         }
         
      </script>
   </body>
</html>