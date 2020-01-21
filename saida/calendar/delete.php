<?php

//delete.php

if (isset($_POST["id"])) {
    $connect   = new PDO('mysql:host=localhost;dbname=maorke_saida', 'maorke', 'P@j@Lp3-a=ZE8CE@');
    $query     = "
 DELETE from events WHERE id=:id
 ";
    $statement = $connect->prepare($query);
    $statement->execute(array(
        ':id' => $_POST['id']
    ));
}

?>