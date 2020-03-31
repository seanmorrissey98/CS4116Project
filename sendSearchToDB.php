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
$interests_map = array('soccer' => 1, 'sport' => 2, 'film' => 3, 'painting' => 4);
$interests = '';

/*foreach ($key as array_keys($interests_map)) {
    if (isset($_POST[$key])) {
        $interests = add_interest($interests, $interests_map[$key]);
    }
}*/

if (isset($_POST['soccer'])) {
    $interests = add_interest($interests, $interests_map['soccer']);
}
if (isset($_POST['sport'])) {
    $interests = add_interest($interests, $interests_map['sport']);
}
if (isset($_POST['film'])) {
    $interests = add_interest($interests, $interests_map['film']);
}
if (isset($_POST['painting'])) {
    $interests = add_interest($interests, $interests_map['painting']);
}

if ($interests != '') $interests .= ')';

function add_interest($interests, $i)
{
    if ($interests == '') $interests = "AND Interests.interest_id IN (" . $i; else
        $interests .= ', ' . $i;
    return $interests;
}

echo '<br>';
print_r($hobbies_arr);

print(implode("|", $hobbies_arr));

echo '\\n';
print('we want: ' . 'min age: ' . $min_age . ' max age: ' . $max_age . ', gender: ' . $gender . ', drinker: ' . $drinker . ', smoker: ' . $smoker . ', hobbies: ' . $interests);

// SQL Query
$sql = "SELECT User.user_id, first_name, last_name, Age, Photo, Description, Gender, Drinker, Smoker, GROUP_CONCAT(Interests.interest_id) AS interests FROM User INNER JOIN Profile ON User.user_id = Profile.user_id LEFT JOIN Interests ON User.user_id = Interests.user_id LEFT JOIN Dislikes ON User.user_id = Dislikes.disliked_user_id LEFT JOIN Likes ON User.user_id = Likes.liked_user_id LEFT JOIN Reports ON User.user_id = Reports.reported_user_id WHERE (Dislikes.disliked_user_id IS NULL OR Dislikes.user_id != 5) AND (Likes.liked_user_id IS NULL OR Likes.user_id != 5) AND (Reports.reported_user_id IS NULL OR Reports.user_id != 5) AND Age BETWEEN " . $min_age . " AND " . $max_age . " " . $drinker . " " . $gender . " " . $interests . " GROUP BY User.user_id HAVING User.user_id != 5 LIMIT 20";
echo '<br>';


// Execute query to database
$result = mysqli_query($con, $sql);
$matched_data = $result->fetch_all(MYSQLI_ASSOC);
$_SESSION['advanced_search_result'] = $matched_data;

// Return to discover page
header("location: discover.php");
?>