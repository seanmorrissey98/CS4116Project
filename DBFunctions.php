<?php

// Include config file
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once "connection.php";

// Twig for templating matched cards. Stored in templates directory
require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

function getDiscoverPeople($user_id)
{
    global $con;

    // Pull 1 row from database from like where matches not in dislike, like and report pages
    $sql = "SELECT User.user_id, first_name, last_name, Age, Photo, Description, Gender, Drinker, Smoker FROM User INNER JOIN Profile ON User.user_id = Profile.user_id LEFT JOIN Dislikes ON User.user_id = Dislikes.disliked_user_id LEFT JOIN Likes ON User.user_id = Likes.liked_user_id LEFT JOIN Reports ON User.user_id = Reports.reported_user_id WHERE (Dislikes.disliked_user_id IS NULL OR Dislikes.user_id != '" . $user_id . "') AND (Likes.liked_user_id IS NULL OR Likes.user_id != '" . $user_id . "') AND (Reports.reported_user_id IS NULL OR Reports.user_id != '" . $user_id . "') HAVING User.user_id != '" . $user_id . "' LIMIT 1";
    $result = mysqli_query($con, $sql);

    $people = $result->fetch_all(MYSQLI_ASSOC);

    return $people;
}

function getMatches($user_id)
{
    global $con;

    $matchesSql = "SELECT User.user_id, first_name, Photo FROM Likes INNER JOIN User ON Likes.liked_user_id = User.user_id LEFT JOIN Profile ON Likes.liked_user_id = Profile.user_id WHERE Likes.user_id = " . $user_id;
    $match_result = mysqli_query($con, $matchesSql);
    $matched_data = $match_result->fetch_all(MYSQLI_ASSOC);

    return $matched_data;
}

function getMessagesForChat($chat_id)
{
    global $con, $twig;

    $sql = "SELECT * FROM Messages WHERE chat_id = " . $chat_id . " ORDER BY message_timestamp";

    $result = mysqli_query($con, $sql);
    //$no_result = mysqli_num_rows($result) === 0;
    $messages = $result->fetch_all(MYSQLI_ASSOC);

    $message_html = $twig->render('messages_template.html.twig', ['messages' => $messages, 'user_id' => $_SESSION['user_id']]);
    $message_html = str_replace("\n", '', $message_html);

    return $message_html;
}