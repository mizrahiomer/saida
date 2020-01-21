<?php

//update.php

 $connect = new PDO('mysql:host=localhost;dbname=maorke_saida', 'maorke', 'P@j@Lp3-a=ZE8CE@');
 $connect->exec('set names utf8');

if(isset($_POST["id"]))
{
 $query = "
 UPDATE events 
 SET title=:title, start_event=:start_event, end_event=:end_event 
 WHERE id=:id
 ";
 $statement = $connect->prepare($query);
 $statement->execute(
  array(
   ':title'  => $_POST['title'],
   ':start_event' => $_POST['start'],
   ':end_event' => $_POST['end'],
   ':id'   => $_POST['id']
  )
 );
}

?>