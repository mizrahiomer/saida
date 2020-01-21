<?php
require_once('init.php');

class weekShifts
{
    public static function insertShifts($uid, $sunday, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday)
    {
        global $database;
        $sql = "UPDATE requests SET sunday='" . $sunday . "', monday='" . $monday . "', tuesday='" . $tuesday . "', wednesday='" . $wednesday . "', thursday='" . $thursday . "', friday='" . $friday . "', saturday='" . $saday . "' WHERE uid=$uid";
        $result = $database->query($sql) or die("error inserting data");
    }
}
?>