<?php

// Include config file
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once "DBFunctions.php";
require_once "TwigFunctions.php";

// Initialize the session
session_start();

// Twig for templating matched cards. Stored in templates directory
require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

if (isset($_POST['timestamp'])) $messages = getMessagesForChatLatest($_POST['chat_id'], $_POST['timestamp'], $_SESSION['user_id']); else
    $messages = getMessagesForChat($_POST['chat_id']);

$message_html = getTwigMessages($messages, $_SESSION['user_id']);

echo json_encode($message_html);