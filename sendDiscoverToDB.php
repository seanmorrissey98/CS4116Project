<?php
// Include config file
require_once "connection.php";

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
    $sql = "INSERT INTO `" . $tableName . "`(`user_id`, `" . $columnName . "`) VALUES( " . $_SESSION["user_id"] . "," . $_POST['match_id'] . ")";
}

// Execute query to database
if ($con->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

// Remove Search Result after Like / Dislike / Report
foreach ($_SESSION['advanced_search_result'] as $key => $value) {
    if ($value['user_id'] == $_POST['match_id']) {
        unset($_SESSION['advanced_search_result'][$key]);
    }
}

// Return to discover page
header("location: discover.php");
?>