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

print_r($_POST);

// Getting values from form
// Age
$min_age = '';
$max_age = '';
$age_arr = array();

if (isset($_POST['age'])) {
    $age_arr = explode(',', $_POST['age']);
    $max_age = array_pop($age_arr);
    $min_age = array_pop($age_arr);
}

// Gender
$gender = "";
if (isset($_POST['male'])) {
    $gender = add_gender($gender, 'Male');
}
if (isset($_POST['female'])) {
    $gender = add_gender($gender, 'Female');
}
if (isset($_POST['other'])) {
    $gender = add_gender($gender, 'Other');
}

if ($gender != '') $gender .= ')';

function add_gender($gender, $g)
{
    if ($gender == '') $gender = "AND Gender IN ('" . $g . "'"; else
        $gender .= ", '" . $g . "'";
    return $gender;
}

// Drinker
$drinker = "";
if (isset($_POST['drinker-radio']) && $_POST['drinker-radio'] != 'any') {
    $drinker = "AND Drinker IN ('Constantly', 'Most days', 'Social Drinker')";
} else {
    $drinker = '';
}

// Smoker
$smoker = '';
if (isset($_POST['smoker-radio'])) {
    $smoker = "AND Smoker = " . $_POST['smoker-radio'];
}

// Hobbies
$hobbies_arr = array();
$hobbies_str = array();

// Map to ints
$interests_map = array('Soccer' => 1, 'Knitting' => 2, 'Film' => 3, 'Painting' => 4, 'Photography' => 5, 'Dance' => 6, 'Gardening' => 7, 'Exercise' => 8, 'Cooking' => 9, 'Video Games' => 10, 'Shopping' => 11, 'Music' => 12, 'Cycling' => 13, 'Programming' => 14, 'Archery' => 15);
$interests = '';

/*foreach ($key as array_keys($interests_map)) {
    if (isset($_POST[$key])) {
        $interests = add_interest($interests, $interests_map[$key]);
    }
}*/

if (isset($_POST['Soccer'])) {
    $interests = add_interest($interests, $interests_map['Soccer']);
}
if (isset($_POST['Knitting'])) {
    $interests = add_interest($interests, $interests_map['Knitting']);
}
if (isset($_POST['Film'])) {
    $interests = add_interest($interests, $interests_map['Film']);
}
if (isset($_POST['Painting'])) {
    $interests = add_interest($interests, $interests_map['Painting']);
}
if (isset($_POST['Photography'])) {
    $interests = add_interest($interests, $interests_map['Photography']);
}
if (isset($_POST['Dance'])) {
    $interests = add_interest($interests, $interests_map['Dance']);
}
if (isset($_POST['Gardening'])) {
    $interests = add_interest($interests, $interests_map['Gardening']);
}
if (isset($_POST['Exercise'])) {
    $interests = add_interest($interests, $interests_map['Exercise']);
}
if (isset($_POST['Cooking'])) {
    $interests = add_interest($interests, $interests_map['Cooking']);
}
if (isset($_POST['Video Games'])) {
    $interests = add_interest($interests, $interests_map['Video Games']);
}
if (isset($_POST['Shopping'])) {
    $interests = add_interest($interests, $interests_map['Shopping']);
}
if (isset($_POST['Music'])) {
    $interests = add_interest($interests, $interests_map['Music']);
}
if (isset($_POST['Cycling'])) {
    $interests = add_interest($interests, $interests_map['Cycling']);
}
if (isset($_POST['Programming'])) {
    $interests = add_interest($interests, $interests_map['Programming']);
}
if (isset($_POST['Archery'])) {
    $interests = add_interest($interests, $interests_map['Archery']);
}

if ($interests != '') $interests .= ')';

function add_interest($interests, $i) {
    if ($interests == '') $interests = "AND Interests.interest_id IN (" . $i; else
        $interests .= ', ' . $i;
    return $interests;
}

echo '<br>';
print_r($hobbies_arr);

print(implode("|", $hobbies_arr));

echo '\\n';
//print('we want: ' . 'min age: ' . $min_age . ' max age: ' . $max_age . ', gender: ' . $gender . ', drinker: ' . $drinker . ', smoker: ' . $smoker . ', hobbies: ' . $interests);

// SQL Query
$sql = "SELECT User.user_id, first_name, last_name, Age, Photo, Description, Gender, Drinker, Smoker, GROUP_CONCAT(Interests.interest_id) AS interests FROM User INNER JOIN Profile ON User.user_id = Profile.user_id LEFT JOIN Interests ON User.user_id = Interests.user_id LEFT JOIN Dislikes ON User.user_id = Dislikes.disliked_user_id LEFT JOIN Likes ON User.user_id = Likes.liked_user_id LEFT JOIN Reports ON User.user_id = Reports.reported_user_id WHERE (Dislikes.disliked_user_id IS NULL OR Dislikes.user_id != 5) AND (Likes.liked_user_id IS NULL OR Likes.user_id != 5) AND (Reports.reported_user_id IS NULL OR Reports.user_id != 5) AND Age BETWEEN " . $min_age . " AND " . $max_age . " " . $drinker . " " . $gender . " " . $interests . " AND NOT User.user_type = 'administrator' GROUP BY User.user_id HAVING User.user_id != " . $_SESSION['user_id'] . " LIMIT 20";

// Execute query to database
$result = mysqli_query($con, $sql);
$matched_data = $result->fetch_all(MYSQLI_ASSOC);
$_SESSION['advanced_search_result'] = $matched_data;

// Return to discover page
header("location: discover.php");
?>