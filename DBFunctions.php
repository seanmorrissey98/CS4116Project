<?php

require_once "connection.php";

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

function getChats($user_id)
{
    global $con;

    // Get all chat data for the 'Messages' Side Panel
    $chatsSql = "SELECT chat_id, IF(user_id_receiver = " . $user_id . ", `user_id_sender`, `user_id_receiver`) AS user_id_receiver, latest_message, latest_message_timestamp, first_name, Photo, Age, Description FROM Chat INNER JOIN User ON User.user_id = IF(user_id_receiver = " . $user_id . ", `user_id_sender`, `user_id_receiver`) INNER JOIN Profile ON Profile.user_id = IF(user_id_receiver = " . $user_id . ", `user_id_sender`, `user_id_receiver`) WHERE Chat.user_id_sender = " . $user_id . " OR Chat.user_id_receiver = " . $user_id . " ORDER BY Chat.latest_message_timestamp DESC";
    $result = mysqli_query($con, $chatsSql);
    $chats_data = $result->fetch_all(MYSQLI_ASSOC);

    return $chats_data;
}

function getMessagesForChat($chat_id)
{
    global $con;

    $sql = "SELECT * FROM Messages WHERE chat_id = " . $chat_id . " ORDER BY message_timestamp";

    $result = mysqli_query($con, $sql);
    //$no_result = mysqli_num_rows($result) === 0;
    $messages = $result->fetch_all(MYSQLI_ASSOC);

    return $messages;
}

function getMessagesForChatLatest($chat_id, $timestamp, $user_id)
{
    global $con;

    $sql = "SELECT * FROM Messages WHERE message_timestamp > '" . $timestamp . "' AND chat_id = " . $chat_id . " AND user_id_receiver = " . $user_id . " ORDER BY message_timestamp";

    $result = mysqli_query($con, $sql);
    //$no_result = mysqli_num_rows($result) === 0;
    $messages = $result->fetch_all(MYSQLI_ASSOC);

    return $messages;
}
