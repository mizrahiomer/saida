<?php
session_start();
require_once 'init.php';
$uid       = $_SESSION['uid'];
$statusMsg = '';

// File upload path
$targetDir      = "../sidebar/img/";
$fileName       = $uid . '-' . basename($_FILES["image"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType       = pathinfo($targetFilePath, PATHINFO_EXTENSION);

if (!empty($_FILES["image"]["name"])) {
    // Allow certain file formats
    $allowTypes = array(
        'jpg',
        'png',
        'jpeg',
        'gif'
    );
    if (in_array($fileType, $allowTypes)) {
        // Upload file to server
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            // Insert image file name into database
            $insert = $database->query("UPDATE users SET image = '" . $fileName . "' WHERE uid='$uid'");
            if ($insert) {
                $statusMsg = "The file " . $fileName . " has been uploaded successfully.";
            } else {
                $statusMsg = "File upload failed, please try again.";
            }
        } else {
            $statusMsg = "Sorry, there was an error uploading your file.";
        }
    } else {
        $statusMsg = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.';
    }
} else {
    $statusMsg = 'Please select a file to upload.';
}

// Display status message
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>