<?php  
   session_start();
   require_once ('../includes/init.php');
   $user = new User();
   $role = $_SESSION['role'];
   if (!$user->get_session()){
   header("location:../login.php");
   exit();
   }
   if($role != 'מנהל'){
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
      <link rel="stylesheet" href="https://bootswatch.com/4/minty/bootstrap.css">
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
      <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
      <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" />
      <title>ניהול משמרות עובדים</title>
      <style>
         .swal-text {
         text-align: center;
         }
      </style>
   </head>
   <body dir="rtl">
      <div class="container-fluid">
         <div dir="rtl" class="row">
            <button class="btn btn-primary m-1" name="back" id="back" onclick="goBack()">חזרה לעמוד הבית</button>
         </div>
         <h1 class="text-center">ניהול משמרות - סעידה בפארק</h1>
         <div dir="rtl" class="text-center" id="jsGrid"></div>
      </div>
      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script type="text/javascript" src="jsgrid.min.js"></script>
      <script type="text/javascript" src="jsgrid-locale-he.js"></script>
      <script>
         var role = <?php if($role != "מנהל"){ echo 'false'; } else { echo 'true'; }?>;
         if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                 if(role){
                     $('.container-fluid').hide();
                     swal({
                        title: "אהלן וסהלן",
                        text: "סמוך עלינו, יותר נוח להתשתמש במאגר משמרות מהמחשב",
                        icon: "warning",
                        button: "סבבה, הבנתי",
                        dangerMode: true
                     }).then(function(){
                         window.history.back();
                     });
              
                 }
             }
         jsGrid.locale("he");
         jsGrid.sortStrategies.date = function(a, b) {
             var aa = a.split('.').reverse().join(),
                 bb = b.split('.').reverse().join();
             return aa < bb ? -1 : (aa > bb ? 1 : 0);
         };
         $('#jsGrid').jsGrid({
             width: "100%",
             height: "600px",
             filtering: true,
             inserting: role,
             editing: role,
             sorting: true,
             paging: true,
             autoload: true,
             pageSize: 10,
             pageButtonCount: 5,
             confirmDeleting: false,
             onItemDeleting: function(args) {
                 if (!args.item.deleteConfirmed) {
                     args.cancel = true;
                     swal({
                             reverseButtons: true,
                             title: "מחיקת משמרת",
                             text: "האם אתה בטוח?",
                             icon: "warning",
                             buttons: ["ביטול", "אישור"],
                             dangerMode: true,
                         })
                         .then((willDelete) => {
                             if (willDelete) {
                                 args.item.deleteConfirmed = true;
                                 $('#jsGrid').jsGrid('deleteItem', args.item);
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
                         });
                 }
             },
             onItemUpdated: function(args) {
                 $("#jsGrid").jsGrid("loadData");
                 swal({
                     title: "עודכן בהצלחה",
                     icon: "success",
                 });
             },
             onItemInserting: function(args) {
                 swal({
                     title: "נוסף בהצלחה",
                     icon: "success",
                 });
             },
             onItemInserted: function(args) {
                 $("#jsGrid").jsGrid("loadData");
             },
             controller: {
                 loadData: function(filter) {
                     return $.ajax({
                         type: "GET",
                         url: "fetch_data.php",
                         data: filter,
                     });
                 },
                 insertItem: function(item) {
                     return $.ajax({
                         type: "POST",
                         url: "fetch_data.php",
                         data: item,
                     });
                 },
                 updateItem: function(item) {
                     return $.ajax({
                         type: "PUT",
                         url: "fetch_data.php",
                         data: item
                     });
                 },
                 deleteItem: function(item) {
                     return $.ajax({
                         type: "DELETE",
                         url: "fetch_data.php",
                         data: item
                     });
                 },
             },
             fields: [{
                     name: "#",
                     visible: false,
                 },
                 {
                     name: "fname",
                     title: "שם פרטי",
                     type: "text",
                     width: 150,
                     validate: "required",
                     editing : false
                 },
                 {
                     name: "lname",
                     type: "text",
                     title: "שם משפחה",
                     width: 150,
                     validate: "required",
                     editing : false
                 },
                 {
                     name: "date",
                     type: "text",
                     title: "תאריך",
                     width: 100,
                     sorter: "date",
                     editing: false,
                     validate: "required"
                 },
                 {
                     name: "role",
                     title: "תפקיד",
                     type: "select",
                     items: [{
                             Name: "",
                             Id: ''
                         },
                         {
                             Name: "מלצר",
                             Id: 'מלצר'
                         },
                         {
                             Name: "ברמן",
                             Id: 'ברמן'
                         },
                         {
                             Name: 'אחמ"ש',
                             Id: 'אחמ"ש'
                         },
                         {
                             Name: "מארחת",
                             Id: 'מארחת'
                         },
                         {
                             Name: "טבח",
                             Id: 'טבח'
                         },
                         {
                             Name: "מנהל",
                             Id: 'מנהל'
                         }
                     ],
                     valueField: "Id",
                     textField: "Name",
                     validate: "required",
                     editing : false
                 },
                 {
                     name: "uid",
                     title: "תעודת זהות",
                     type: "text",
                     width: 100,
                     editing : false
                 },
                 {
                     name: "start",
                     title: "תחילת משמרת",
                     type: "text",
                     width: 100,
                     validate: "required"
                 },
                 {
                     name: "end",
                     title: "סיום משמרת",
                     type: "text",
                     width: 100,
                     validate: "required"
                 },
                 {
                     name: "total",
                     title: 'סה"כ',
                     type: "text",
                     width: 100,
                     editing : false
                 },
                 {
                     type: "control",
                     deleteButton: role,
                     editButton: role,
                 }
             ]
         });
      </script>
      <script>
         function goBack() {
           window.location.href="../index.php";
         }
      </script>
   </body>
</html>