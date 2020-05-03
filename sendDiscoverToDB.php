<?php
// Include config file
require_once "connection.php";
include "DBFunctions.php";
// Initialize the session
session_start();









// Create empty variable for the destination Table and Column in database
$tableName = '';
$columnName = '';




// Assign correct table and column names to variables
if (array_key_exists('dislike-button', $_POST)) {
    $tableName = "Dislikes";
    $columnName = "disliked_user_id";
} else if (array_key_exists('like-button', $_POST)) {
    $tableName = "Likes";
    $columnName = "liked_user_id";
    // create_chat($con);
} else if (array_key_exists('report-button', $_POST)) {
    $tableName = "Reports";
    $columnName = "reported_user_id";
}

// Initialise sql string
$sql = "";

// Check if the button press was a report
if (isset($_POST['reportText']) && $tableName === 'Reports') {
    $reason = $_POST['reportText'];
    $sql = "INSERT INTO `" . $tableName . "`(`user_id`, `" . $columnName . "`, `reason`) VALUES( " . $_SESSION["user_id"] . "," . $_POST['match_id'] . ", '" . $reason . "')";
} else {
    if ($tableName == "Likes") {
        $tmp = $_POST['match_id'];
        $check = checkIfConnectionMade($tmp);
        if ($check == true) {
            create_chat($con);
            $userId = $_SESSION['user_id'];
            $id = $_POST['match_id'];
            $date = date("Y-m-d");
            $sql = "INSERT INTO Connection VALUES (DEFAULT,'$userId', '$id', '$date'), (DEFAULT,'$id', '$userId', '$date')"; 
        }
    } else {
        $sql = "INSERT INTO `" . $tableName . "`(`user_id`, `" . $columnName . "`) VALUES( " . $_SESSION["user_id"] . "," . $_POST['match_id'] . ")";
    }
}

// Execute query to database
if ($con->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

// Remove Search Result after Like / Dislike / Report
if (isset($_SESSION['advanced_search_result'])) {
    foreach ($_SESSION['advanced_search_result'] as $key => $value) {
        if ($value['user_id'] == $_POST['match_id']) {
            unset($_SESSION['advanced_search_result'][$key]);
        }
    }
}

function create_chat()
{
    global $con;


    // SQL for creating new chat after like
    $chat_sql = "Select * from Chat where Chat.user_id_sender = " . $_POST['match_id'] . " AND Chat.user_id_receiver = " . $_SESSION["user_id"];
    $chat_result = mysqli_query($con, $chat_sql);

    if ($chat_result && mysqli_num_rows($chat_result) === 0) {
        $sql = "INSERT INTO `Chat`(`user_id_sender`, `user_id_receiver`, `latest_message`) VALUES( " . $_SESSION["user_id"] . "," . $_POST['match_id'] . ", 'New Match! Click to message!')";
        if ($con->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
    }
    mysqli_free_result($chat_result);
}

// Return to discover page
header("location: discover.php");
