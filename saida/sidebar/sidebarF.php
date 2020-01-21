</div>
</div>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>            
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC269suXtEy2Gh3z-oRlRMgUhu32RZPltk&libraries=geometry" type="text/javascript"></script>
<script>
   $(document).ready(function() {
       $('#page-content-wrapper').click(function(e) {
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ){
                if($("#wrapper").hasClass("toggled")){
                    e.preventDefault();
                    $("#wrapper").toggleClass("toggled");
                    $('#arrow').toggleClass('fas fa-chevron-right fas fa-chevron-left');
               }
            }
        });
        
       $("#image").click(function() {
           $("#profile").click();
       });
       $("#profile").change(function() {
           $('#editProfilePic').submit();
       });
       $("#menu-toggle").click(function(e) {
           e.preventDefault();
           $("#wrapper").toggleClass("toggled");
           $('#arrow').toggleClass('fas fa-chevron-right fas fa-chevron-left');
       });
       if ($(window).width() <= 992) {
           $("#wrapper").removeClass("toggled");
           $('#arrow').toggleClass('fas fa-chevron-right fas fa-chevron-left');
    
           
       } else {
           $("#wrapper").addClass("toggled");
         
       }
       $(window).resize(function(e) {
           if ($(window).width() <= 992) {
               $("#wrapper").removeClass("toggled");
               $('#arrow').toggleClass('fas fa-chevron-left fas fa-chevron-right');
            
           } else {
               $("#wrapper").addClass("toggled");
               $('#arrow').toggleClass('fas fa-chevron-right fas fa-chevron-left');
              
                
           }
       });
   computeDistance();
   $.ajax({
       type: "GET",
       url: "../includes/watchedCalendar.php",
       success: function(data) {
           if (data == 0) {
               swal({
                   title: "!הלו הלו",
                   text: "התפרסם סידור עבודה חדש",
                   icon: "warning",
                   dangerMode: true,
                   buttons: "סבבה",
                   closeOnClickOutside: false,
               });
           }
       }
   });
   var status;
   var d = new Date();
   var n = d.getHours() + ":" + d.getMinutes();
   $.ajax({
       type: "GET",
       url: "../includes/inshift.php",
       success: function(data) {
           if (data == 1) {
               $('#inshift').removeClass('btn btn-lg btn-light text-primary').addClass('btn btn-lg btn-danger text-light');
               $('#inshift').html(" ירידה ממשמרת");
               status = 1;
           } else {
               $('#inshift').removeClass('btn btn-lg btn-danger text-light').addClass('btn btn-lg btn-light text-primary');
               $('#inshift').html("עלייה למשמרת");
               status = 0;
           }
       }
   });
   $('#inshift').click(function() {
       var distance = localStorage.getItem("distance");
       if (distance == -1){
           swal({
              title: 'סה"כ ביקשנו לדעת איפה אתה בנאדם',
              text: 'כדי לעלות ו/או לרדת ממשמרת, צריך להפעיל מיקום',
              icon: "warning",
              dangerMode: true,
              button: "סגור, הבנתי",
              closeOnClickOutside: false
           });
       }
       else if (status == 0) {
           if (distance > 3500) {
               swal({
                   title: "?איפה אתם חושבים שאתם נמצאים",
                   text: "אי אפשר לעלות למשמרת אם אתם לא בסעידה",
                   icon: "error",
                   dangerMode: true,
                   button: 'סליחה, שיקרתי'
               });
           }else{
           $('#inshift').removeClass('btn btn-light').addClass('btn btn-danger');
           $('#inshift').html("ירידה ממשמרת\n");
           status = 1;
           $.ajax({
               type: "POST",
               url: "../includes/inshift.php",
               data: {
                   data: status,
                   flag: true,
               },
               success: function(data) {
                   swal({
                      title: "עלית למשמרת בהצלחה",
                      text: "יאללה לתת בראש ועבודה נעימה",
                      icon: "success",
                      button: "אש",
                      closeOnClickOutside: false
                   });
               }
           });
           }
       } else {
           if (distance > 3500) {
               swal({
                   title: "?איפה אתם חושבים שאתם נמצאים",
                   text: "אי אפשר לרדת ממשמרת אם אתם לא בסעידה",
                   icon: "error",
                   dangerMode: true,
                   button: 'סליחה, שיקרתי'
               });
           } else {
               $('#inshift').removeClass('btn btn-danger').addClass('btn btn-light');
               $('#inshift').html("עלייה למשמרת");
               status = 0;
               $.ajax({
                   type: "POST",
                   url: "../includes/inshift.php",
                   data: {
                       data: status,
                       finish: true,
                   },
                   success: function(data) {
                       swal({
                          title: "ירדת ממשמרת בהצלחה",
                          text: "מוזמנים להישאר לשבת ולשתות משהו",
                          icon: "success",
                          closeOnClickOutside: false
                       });
                   }
               });
           }
   
       }
   
   });
   });
   
   function showPosition(position) {
   var my = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
   var saida = new google.maps.LatLng('32.003035', '34.797248');
   distance = google.maps.geometry.spherical.computeDistanceBetween(my, saida);
   localStorage.setItem('distance', distance);
   }
   
   function failAccess(err) {
   localStorage.setItem('distance', -1);
   }
   
   function computeDistance(distance) {
   navigator.geolocation.getCurrentPosition(showPosition, failAccess);
   }
   
</script>