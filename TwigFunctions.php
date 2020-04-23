<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Twig for templating matched cards. Stored in templates directory
require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

function getTwigMessages($messages, $user_id)
{
    global $twig;

    $message_html = $twig->render('messages_template.html.twig', ['messages' => $messages, 'user_id' => $user_id]);
    $message_html = str_replace("\n", '', $message_html);

    return $message_html;
}