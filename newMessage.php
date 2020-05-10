<?php

// Include config file
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once "connection.php";

// Initialize the session
session_start();
if (isset($_SESSION["adminLoggedIn"]) && $_SESSION["adminLoggedIn"] == true) {
    header("Location: adminDashboard.php");
}

// Twig for templating matched cards. Stored in templates directory
require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

$newMessageSQL = "INSERT INTO `Messages` (`chat_id`, `user_id_sender`, `user_id_receiver`, `message`) VALUES(" . $_POST['chat_id'] . ", " . $_SESSION['user_id'] . ", " . $_POST['user_id_receiver'] . ", '" . $_POST['message'] . "');";
$updateChatSQL = "UPDATE `Chat` SET latest_message = '" . $_POST['message'] . "' WHERE chat_id = " . $_POST['chat_id'] . ";";

if ($con->query($newMessageSQL) === TRUE) {
    //echo "New record created successfully";
} else {
    //echo "Error: " . $newMessageSQL . "<br>" . $con->error;
}
if ($con->query($updateChatSQL) === TRUE) {
    //echo "New record created successfully";
} else {
    //echo "Error: " . $updateChatSQL . "<br>" . $con->error;
}

$now = date_create('now')->format('Y-m-d H:i:s'); // works in php 5.2 and higher
$newMessageArray = array((object)['user_id_sender' => $_SESSION['user_id'], 'user_id_receiver' => $_POST['user_id_receiver'], 'message' => $_POST['message'], 'message_timestamp' => $now]);

$message_html = $twig->render('messages_template.html.twig', ['messages' => $newMessageArray, 'user_id' => $_SESSION['user_id']]);
$message_html = str_replace("\n", '', $message_html);

echo json_encode($message_html);