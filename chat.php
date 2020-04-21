<?php

// Include config file
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once "connection.php";

// Initialize the session
session_start();

// Twig for templating matched cards. Stored in templates directory
require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

$sql = "SELECT * FROM Messages WHERE chat_id = " . $_POST['chat_id'] . " ORDER BY message_timestamp";

$result = mysqli_query($con, $sql);
$no_result = mysqli_num_rows($result) === 0;
$messages = $result->fetch_all(MYSQLI_ASSOC);

$message_html = $twig->render('messages_template.html.twig', ['messages' => $messages, 'user_id' => $_SESSION['user_id']]);
$message_html = str_replace("\n", '', $message_html);

echo json_encode($message_html);