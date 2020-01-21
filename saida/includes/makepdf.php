<?php
session_start();
require_once('init.php');
$date     = $_GET['date'];
$sql      = "SELECT * FROM shifts_summaries WHERE date='$date' LIMIT 1";
$result   = $database->query($sql);
$row      = $result->fetch_assoc();
$uid      = $row['managerId'];
$sql      = "SELECT fname,lname FROM users WHERE uid='$uid'";
$result   = $database->query($sql);
$name     = $result->fetch_assoc();
$name     = $name['fname'] . ' ' . $name['lname'];
// Set parameters
$apikey   = 'a5ab16ec-db5e-484b-85d7-7a74e8652e41';
$value    = '<!doctype html>
            <html lang="en">
            <head>
                <title>' . date("d/m/Y", strtotime($date)) . ' </title>
                <!-- Required meta tags -->
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <!-- Bootstrap CSS -->
                <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/minty/bootstrap.min.css" rel="stylesheet" integrity="sha384-9NlqO4dP5KfioUGS568UFwM3lbWf3Uj3Qb7FBHuIuhLoDp3ZgAqPE1/MYLEBPZYM" crossorigin="anonymous">
                <!-- Font Awesome -->
                <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-3 col-md-6 text-center">
                             
                                <div class="card-body">
                                  <div class="col no-gutters align-items-center">
                                    <div class="mr-2">
                                    <br>
                                    <div><img src="http://maorke.mtacloud.co.il/saida/sidebar/img/logo-login.png" alt="Saida Logo"></div>
                                    <br>
                                      <div class="h2 font-weight-bold text-primary mb-1">דו"ח סיכום משמרת לתאריך - <span class="h2 mb-0 font-weight-bold text-800">' . date("d/m/Y", strtotime($date)) . '</span></div>
                                      <div class="h3 font-weight-bold text-primary mb-1">מייצר הדו"ח - <span class="h3 mb-0 font-weight-bold text-800">' . $name . '</span></div>
                                      <br>
                                      <div class="h4 font-weight-bold text-primary mb-1">סה"כ כללי</div>
                                      <div class="h5 mb-0 font-weight-bold text-gray-800">&#8362; ' . $row['total'] . '</div>
                                      <br>
                                      <div class="h4 font-weight-bold text-primary mb-1">סה"כ מזומן</div>
                                      <div class="h5 mb-0 font-weight-bold text-gray-800">&#8362; ' . $row['cash'] . '</div>
                                      <br>
                                      <div class="h4 font-weight-bold text-primary mb-1">סה"כ אשראי</div>
                                      <div class="h5 mb-0 font-weight-bold text-gray-800">&#8362; ' . $row['credit'] . '</div>
                                      <br>
                                      <div class="h4 font-weight-bold text-primary mb-1">סה"כ לקוחות</div>
                                      <div class="h5 mb-0 font-weight-bold text-gray-800">' . $row['customers'] . '</div>
                                      <br>
                                      <div class="h4 font-weight-bold text-primary mb-1">ממוצע סועד </div>
                                      <div class="h5 mb-0 font-weight-bold text-gray-800">&#8362; ' . $row['customer_avg'] . '</div>
                                      <br>
                                      <div class="h4 font-weight-bold text-primary mb-1">סה"כ ביטולים</div>
                                      <div class="h5 mb-0 font-weight-bold text-gray-800">&#8362; ' . $row['cancellations'] . '</div>
                                      <br>
                                      <div class="h4 font-weight-bold text-primary mb-1">סה"כ הנחות</div>
                                      <div class="h5 mb-0 font-weight-bold text-gray-800">&#8362; ' . $row['discounts'] . '</div>
                                      <br>
                                      <div class="h4 font-weight-bold text-primary mb-1">סה"כ טיפים</div>
                                      <div class="h5 mb-0 font-weight-bold text-gray-800">&#8362; ' . $row['tips'] . '</div>
                                      <br>
                                      <div class="h4 font-weight-bold text-primary mb-1">טיפ לשעה </div>
                                      <div class="h5 mb-0 font-weight-bold text-gray-800">&#8362; ' . $row['tips_hour'] . '</div>
                                      <br>
                                      <div class="h4 font-weight-bold text-primary mb-1">סיכום </div>
                                      <div class="h5 mb-0 text-gray-800">' . $row['summary'] . '</div>
                                      
                                    </div>
                                  </div>
                                </div>
                             
                            </div>
                        </div><!--row-->
                    </div><!--container-->
                        <!-- JavaScript -->
                        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
                        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
                        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
                          </body>
        </html>'; // can aso be a url, starting with http..            
$postdata = http_build_query(array(
    'apikey' => $apikey,
    'value' => $value
));

$opts = array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
);

$context = stream_context_create($opts);

// Convert the HTML string to a PDF using those parameters
$result = file_get_contents('http://api.html2pdfrocket.com/pdf', false, $context);

// Save to root folder in website
header('Content-Description: File Transfer');
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename=' . $date . '.pdf');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . strlen($result));

echo $result;
?>