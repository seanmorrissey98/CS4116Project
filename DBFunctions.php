<?php

require_once "connection.php";

function getDiscoverPeople($user_id) {
    global $con;

    // Pull 1 row from database from like where matches not in dislike, like and report pages
    // Old SQL
    //$sql = "SELECT User.user_id, first_name, last_name, Age, Photo, Description, Gender, Drinker, Smoker FROM User INNER JOIN Profile ON User.user_id = Profile.user_id Left Join (Select * from Likes where Likes.user_id = " . $user_id . ") l ON User.user_id = l.liked_user_id Left Join (Select * from Dislikes where Dislikes.user_id = " . $user_id . ") d ON User.user_id = d.disliked_user_id Left Join (Select * from Reports where Reports.user_id = " . $user_id . ") r ON User.user_id = r.reported_user_id Left Join Banned ON User.user_id = Banned.user_id WHERE (l.liked_user_id IS NULL OR l.user_id != " . $user_id . ") AND (d.disliked_user_id IS NULL OR d.user_id != " . $user_id . ") AND (r.reported_user_id IS NULL OR r.user_id != " . $user_id . ") AND (Banned.user_id IS NULL OR Banned.user_id != " . $user_id . ") AND Profile.Smoker LIKE '%' AND Profile.Drinker LIKE '%' AND user_type = 'user' AND User.user_id != " . $user_id . " ORDER BY RAND() LIMIT 1";
    $sql = "SELECT User.user_id, first_name, last_name, Age, Photo, Description, Gender, Drinker, Smoker FROM User INNER JOIN Profile ON User.user_id = Profile.user_id WHERE User.user_id NOT IN (SELECT Dislikes.disliked_user_id FROM Dislikes WHERE Dislikes.user_id = " . $user_id . ") AND User.user_id NOT IN (SELECT Likes.liked_user_id FROM Likes WHERE Likes.user_id = " . $user_id . ") AND User.user_id NOT IN (SELECT Reports.reported_user_id FROM Reports WHERE Reports.user_id = " . $user_id . ") AND User.user_id NOT IN (SELECT Banned.user_id FROM Banned) AND user_type = 'user' AND User.user_id != " . $user_id . " ORDER BY RAND() LIMIT 1";

    $result = mysqli_query($con, $sql);

    $people = $result->fetch_all(MYSQLI_ASSOC);

    return $people;
}

function getDiscoverPeopleSpecific($user_id, $seeking, $drinker, $smoker) {
    global $con;

    $drinker_sql = "";
    if (isset($drinker)) {
        if ($drinker = 'Constantly' || $drinker = 'Most days' || $drinker = 'Social Drinker') $drinker_sql = "AND Drinker IN ('Constantly', 'Most days', 'Social Drinker')"; else
            $drinker_sql = '';

    } else {
        $drinker_sql = '';
    }

    // Smoker
    $smoker_sql = '';
    if (isset($smoker)) {
        $smoker_sql = "AND Smoker = " . $smoker;
    }

    // Pull 1 row from database from like where matches not in dislike, like and report pages
    // Old SQL
    // $sql = "SELECT User.user_id, first_name, last_name, Age, Photo, Description, Gender, Drinker, Smoker FROM User INNER JOIN Profile ON User.user_id = Profile.user_id Left Join (Select * from Likes where Likes.user_id = " . $user_id . ") l ON User.user_id = l.liked_user_id Left Join (Select * from Dislikes where Dislikes.user_id = " . $user_id . ") d ON User.user_id = d.disliked_user_id Left Join (Select * from Reports where Reports.user_id = " . $user_id . ") r ON User.user_id = r.reported_user_id Left Join Banned ON User.user_id = Banned.user_id WHERE (l.liked_user_id IS NULL OR l.user_id != " . $user_id . ") AND (d.disliked_user_id IS NULL OR d.user_id != " . $user_id . ") AND (r.reported_user_id IS NULL OR r.user_id != " . $user_id . ") " . $drinker_sql . " " . $smoker_sql . " AND (Banned.user_id IS NULL OR Banned.user_id != " . $user_id . ") AND Profile.Gender = '" . $seeking . "' AND user_type = 'user' AND User.user_id != " . $user_id . " ORDER BY RAND() LIMIT 1";
    $sql = "SELECT User.user_id, first_name, last_name, Age, Photo, Description, Gender, Drinker, Smoker FROM User INNER JOIN Profile ON User.user_id = Profile.user_id WHERE User.user_id NOT IN (SELECT Dislikes.disliked_user_id FROM Dislikes WHERE Dislikes.user_id = " . $user_id . ") AND User.user_id NOT IN (SELECT Likes.liked_user_id FROM Likes WHERE Likes.user_id = " . $user_id . ") AND User.user_id NOT IN (SELECT Reports.reported_user_id FROM Reports WHERE Reports.user_id = " . $user_id . ") AND User.user_id NOT IN (SELECT Banned.user_id FROM Banned) AND user_type = 'user' AND Profile.Gender = '" . $seeking . "' AND User.user_id != " . $user_id . " $drinker_sql " . $smoker_sql . " ORDER BY RAND() LIMIT 1";
    $result = mysqli_query($con, $sql);

    $people = $result->fetch_all(MYSQLI_ASSOC);

    return $people;
}

function getMatches($user_id) {
    global $con;

    $matchesSql = "SELECT User.user_id, first_name, Photo FROM Likes INNER JOIN User ON Likes.liked_user_id = User.user_id LEFT JOIN Profile ON Likes.liked_user_id = Profile.user_id WHERE Likes.user_id = " . $user_id . " ORDER BY Likes.date DESC";
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
