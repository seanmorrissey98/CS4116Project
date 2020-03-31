<?php
// Include config file
require_once "connection.php";

// Twig for templating matched cards. Stored in templates directory
require __DIR__ . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

// Initialize the session
session_start();
$user_id = $_SESSION["user_id"];

// Iniialize array for database pull
$matchdata = array();
$matched_data = array();

// Pull 1 row from database from like where matches not in dislike, like and report pages
$sql = "SELECT User.user_id, first_name, last_name, Age, Photo, Description, Gender, Drinker, Smoker FROM User INNER JOIN Profile ON User.user_id = Profile.user_id LEFT JOIN Dislikes ON User.user_id = Dislikes.disliked_user_id LEFT JOIN Likes ON User.user_id = Likes.liked_user_id LEFT JOIN Reports ON User.user_id = Reports.reported_user_id WHERE (Dislikes.disliked_user_id IS NULL OR Dislikes.user_id != '$user_id') AND (Likes.liked_user_id IS NULL OR Likes.user_id != '$user_id') AND (Reports.reported_user_id IS NULL OR Reports.user_id != '$user_id') HAVING User.user_id != '$user_id' order by last_name LIMIT 1";
$matchesSql = "SELECT User.user_id, first_name, Photo FROM Likes INNER JOIN User ON Likes.liked_user_id = User.user_id LEFT JOIN Profile ON Likes.liked_user_id = Profile.user_id WHERE Likes.user_id = $user_id";
$result = mysqli_query($con, $sql);
$match_result = mysqli_query($con, $matchesSql);

$out_of_matches = mysqli_num_rows($result) === 0;
$no_matches = mysqli_num_rows($match_result) === 0;

$matched_data = $match_result->fetch_all(MYSQLI_ASSOC);

$matched_cards = $twig->render('matched_users_template.html.twig', ['matched_data' => $matched_data]);

// Check the array is not empty
if (!$out_of_matches) {
    $nextmatchDate = $result->fetch_all(MYSQLI_ASSOC)[0];

    // If no photo avaliable, set photo to NULL
    if (empty($nextmatchDate['Photo'])) {
        $match_photo = 'NULL';
    } else {
        $match_photo = $nextmatchDate['Photo'];
    }

    // Load match data into Session array
    $_SESSION["match_id"] = $nextmatchDate['user_id'];
    $_SESSION["match_name"] = $nextmatchDate['first_name'] . " " . $nextmatchDate['last_name'];
    $_SESSION["match_age"] = $nextmatchDate['Age'];
    $_SESSION["match_description"] = $nextmatchDate['Description'];
    $_SESSION["match_gender"] = $nextmatchDate['Gender'];
    $_SESSION["match_drinker"] = $nextmatchDate['Drinker'];
    $_SESSION["match_smoker"] = $nextmatchDate['Smoker'];
}
?>

<!DOCTYPE html>
<html style="height: 100%;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Dating Site</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Actor">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/material-icons.min.css">
    <link rel="stylesheet" href="assets/css/Article-List.css">
    <link rel="stylesheet" href="assets/css/Footer-Basic.css">
    <link rel="stylesheet" href="assets/css/Header-Blue.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/css/bootstrap-slider.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">
    <link rel="stylesheet" href="assets/css/Image-slider-carousel-With-arrow-buttons-1.css">
    <link rel="stylesheet" href="assets/css/Image-slider-carousel-With-arrow-buttons.css">
    <link rel="stylesheet" href="assets/css/Lightbox-Gallery.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Dark.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean.css">
    <link rel="stylesheet" href="assets/css/Profile-Card.css">
    <link rel="stylesheet" href="assets/css/Profile-Edit-Form-1.css">
    <link rel="stylesheet" href="assets/css/Profile-Edit-Form.css">
    <link rel="stylesheet" href="assets/css/Registration-Form-with-Photo.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body style="background-color: rgb(255,255,255);height: 100% !important;">
<nav class="navbar navbar-light navbar-expand-md navigation-clean" id="discover-navbar">
    <div class="container">
        <button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span
                    class="navbar-toggler-icon"
                    style="opacity: 1;filter: brightness(200%) hue-rotate(0deg) invert(100%);"></span></button>
        <div
                class="collapse navbar-collapse" id="navcol-1">
            <ul class="nav navbar-nav ml-auto d-flex justify-content-between" id="discover-nav">
                <li class="nav-item" role="presentation" id="messaging-link"><a class="nav-link" id="messaging-nav"
                                                                                href="messaging.html"
                                                                                style="color: #ffffff;">Messages</a>
                </li>
                <li class="nav-item" role="presentation" id="discover-link-1"><a class="nav-link active"
                                                                                 id="discover-nav-1" href="discover.php"
                                                                                 style="color: #ffffff;">Discover</a>
                </li>
                <li class="nav-item" role="presentation"><a class="nav-link" href="accountInfo.php"
                                                            style="color: #ffffff;">Profile</a></li>
                <form method="post">
                    <input type="text" placeholder="Search.." name="search">
                    <button type="submit" name="submit1"><i class="fa fa-search"></i></button>
                    <input type="checkbox" id="nonsmoker" name="nonsmoker" value="nonsmoker">
                    <label for="nonsmoker" style="color: #ffffff;"> Non-Smoker</label>
                    <input type="checkbox" id="nondrinker" name="nondrinker" value="nondrinker">
                    <label for="nondrinker"style="color: #ffffff;"> Non-Drinker</label>
                    <button type="reset" value="Reset">Reset</button>
                    <button type="cancel" value="Cancel">Cancel</button>

                </form>
            </ul>
        </div>
    </div>
</nav>
<?php
if ((isset($_POST['submit1']))) {
    if ((isset($_POST['nonsmoker'])) && (isset($_POST['nondrinker']))) {
        if ($_POST['search']){
        $sql = "SELECT User.user_id, first_name, last_name, Age, Photo, Description, Gender, Drinker, Smoker FROM User INNER JOIN Profile ON User.user_id = Profile.user_id  LEFT JOIN Dislikes ON User.user_id = Dislikes.disliked_user_id LEFT JOIN Likes ON User.user_id = Likes.liked_user_id LEFT JOIN Reports ON User.user_id = Reports.reported_user_id WHERE (Dislikes.disliked_user_id IS NULL OR Dislikes.user_id != '$user_id') AND (Likes.liked_user_id IS NULL OR Likes.user_id != '$user_id') AND (Reports.reported_user_id IS NULL OR Reports.user_id != '$user_id') HAVING User.first_name='$_POST[search]' or User.last_name='$_POST[search]' or Age='$_POST[search]' and Profile.Smoker=0 and Profile.Drinker='no'";
        }
        else{
            $sql = "SELECT User.user_id, first_name, last_name, Age, Photo, Description, Gender, Drinker, Smoker FROM User INNER JOIN Profile ON User.user_id = Profile.user_id  LEFT JOIN Dislikes ON User.user_id = Dislikes.disliked_user_id LEFT JOIN Likes ON User.user_id = Likes.liked_user_id LEFT JOIN Reports ON User.user_id = Reports.reported_user_id WHERE (Dislikes.disliked_user_id IS NULL OR Dislikes.user_id != '$user_id') AND (Likes.liked_user_id IS NULL OR Likes.user_id != '$user_id') AND (Reports.reported_user_id IS NULL OR Reports.user_id != '$user_id') HAVING Profile.Smoker=0 and Profile.Drinker='no'";

        }
    }
    elseif((isset($_POST['nonsmoker'])) && !(isset($_POST['nondrinker']))){
        if ($_POST['search']){
            $sql = "SELECT User.user_id, first_name, last_name, Age, Photo, Description, Gender, Drinker, Smoker FROM User INNER JOIN Profile ON User.user_id = Profile.user_id  LEFT JOIN Dislikes ON User.user_id = Dislikes.disliked_user_id LEFT JOIN Likes ON User.user_id = Likes.liked_user_id LEFT JOIN Reports ON User.user_id = Reports.reported_user_id WHERE (Dislikes.disliked_user_id IS NULL OR Dislikes.user_id != '$user_id') AND (Likes.liked_user_id IS NULL OR Likes.user_id != '$user_id') AND (Reports.reported_user_id IS NULL OR Reports.user_id != '$user_id') HAVING User.first_name='$_POST[search]' or User.last_name='$_POST[search]' or Age='$_POST[search]' and Profile.Smoker=0";

            }
            else{
                $sql = "SELECT User.user_id, first_name, last_name, Age, Photo, Description, Gender, Drinker, Smoker FROM User INNER JOIN Profile ON User.user_id = Profile.user_id  LEFT JOIN Dislikes ON User.user_id = Dislikes.disliked_user_id LEFT JOIN Likes ON User.user_id = Likes.liked_user_id LEFT JOIN Reports ON User.user_id = Reports.reported_user_id WHERE (Dislikes.disliked_user_id IS NULL OR Dislikes.user_id != '$user_id') AND (Likes.liked_user_id IS NULL OR Likes.user_id != '$user_id') AND (Reports.reported_user_id IS NULL OR Reports.user_id != '$user_id') HAVING Profile.Smoker=0";

            }
    }
    elseif((isset($_POST['nondrinker'])) && !(isset($_POST['nonsmoker']))){
        if ($_POST['search']){
            $sql = "SELECT User.user_id, first_name, last_name, Age, Photo, Description, Gender, Drinker, Smoker FROM User INNER JOIN Profile ON User.user_id = Profile.user_id  LEFT JOIN Dislikes ON User.user_id = Dislikes.disliked_user_id LEFT JOIN Likes ON User.user_id = Likes.liked_user_id LEFT JOIN Reports ON User.user_id = Reports.reported_user_id WHERE (Dislikes.disliked_user_id IS NULL OR Dislikes.user_id != '$user_id') AND (Likes.liked_user_id IS NULL OR Likes.user_id != '$user_id') AND (Reports.reported_user_id IS NULL OR Reports.user_id != '$user_id') HAVING User.first_name='$_POST[search]' or User.last_name='$_POST[search]' or Age='$_POST[search]' and Profile.Drinker='no'";

            }
            else{
                $sql = "SELECT User.user_id, first_name, last_name, Age, Photo, Description, Gender, Drinker, Smoker FROM User INNER JOIN Profile ON User.user_id = Profile.user_id  LEFT JOIN Dislikes ON User.user_id = Dislikes.disliked_user_id LEFT JOIN Likes ON User.user_id = Likes.liked_user_id LEFT JOIN Reports ON User.user_id = Reports.reported_user_id WHERE (Dislikes.disliked_user_id IS NULL OR Dislikes.user_id != '$user_id') AND (Likes.liked_user_id IS NULL OR Likes.user_id != '$user_id') AND (Reports.reported_user_id IS NULL OR Reports.user_id != '$user_id') HAVING Profile.Drinker='no'";

            }
    }
    else{
    $sql = "SELECT User.user_id, first_name, last_name, Age, Photo, Description, Gender, Drinker, Smoker FROM User INNER JOIN Profile ON User.user_id = Profile.user_id  LEFT JOIN Dislikes ON User.user_id = Dislikes.disliked_user_id LEFT JOIN Likes ON User.user_id = Likes.liked_user_id LEFT JOIN Reports ON User.user_id = Reports.reported_user_id WHERE (Dislikes.disliked_user_id IS NULL OR Dislikes.user_id != '$user_id') AND (Likes.liked_user_id IS NULL OR Likes.user_id != '$user_id') AND (Reports.reported_user_id IS NULL OR Reports.user_id != '$user_id') HAVING User.first_name='$_POST[search]' or User.last_name='$_POST[search]' or Age='$_POST[search]'";
    }
    $result = mysqli_query($con, $sql);
        if (mysqli_num_rows($result) != 0) {

        foreach ($result as $value) {
            echo implode("",$value);
            echo '<br>';
            if (empty($value['Photo'])) {
                $match_photo = 'NULL';
            } else {
                $match_photo = $value['Photo'];
            }

            // Load match data into Session array
            $_SESSION["match_id"] = $value['user_id'];
            $_SESSION["match_name"] = $value['first_name'] . " " . $value['last_name'];
            $_SESSION["match_age"] = $value['Age'];
            $_SESSION["match_description"] = $value['Description'];
            $_SESSION["match_gender"] = $value['Gender'];
            $_SESSION["match_drinker"] = $value['Drinker'];
            $_SESSION["match_smoker"] = $value['Smoker'];
        }
    } else {
        $match_photo = 'NULL';
        // Load match data into Session array
        $_SESSION["match_id"] = "NULL";
        $_SESSION["match_name"] = "USER NOT FOUND";
        $_SESSION["match_age"] = "NULL";
        $_SESSION["match_description"] = "NULL";
        $_SESSION["match_gender"] = NULL;
        $_SESSION["match_drinker"] = NULL;
        $_SESSION["match_smoker"] = NULL;
    }
}
?>
<div id="new-people-section" class="container-fluid"
     style="background-color: rgba(223,232,238,0);padding: 0;height: calc(100% - 84px);">
    <div class="row" style="margin-right: 0;height: 100%;">
        <div class="col col-xl-3 col-md-4" id="messaging-sidebar" style="padding: 0;">
            <div id="messaging" class="container-fluid" style="padding-right: 0;">
                <header id="message-header" style="background-color: #f7f9fc; margin-bottom: 1rem;"><p
                            id="matched-users" style="margin: -0.7rem;">Matches</p></header>
                <div class="container-fluid">
                    <div class="row">
                        <!-- Templating -->
                        <?php echo $matched_cards; ?>
                    </div>
                </div>
                <div class="row justify-content-center" style="<?php if (!$no_matches) echo " display:none;"; ?>">
                    <p>Start liking to get matches!</p>
                </div>
            </div>
        </div>
        <div class="col col-xl-9 col-md-8 col-xs-12" id="discover-main"
             style="background-color: rgba(221,221,221,0.19);padding: 0 !important;height: 100%;<?php if ($out_of_matches) echo " display:none;"; ?>">
                <div class="container d-block d-block" id="discover-people" style="height: auto;/*background-color: #f7f9fc;*/padding-bottom: 0;">
                    <div class="col-xl-6 offset-xl-3 col-md-8 offset-md-2 col-xs-10 offset-xs-1">
                        <div id="discover-wrapper" class="discover-people-sizing">
                            <div class="row" id="match-photos">
                                <div class="col" id="image" style="padding: 0;"><img class="rounded float-left" src="assets/img/Woman%20Standing%20Infront%20On%20Man%20Hands%20Over%20Arm.jpg" width="320px" style="float: none !important;position: relative;"><button class="btn btn-primary" id="button-left" type="button" style="background-image: url(&quot;assets/img/keyboard_arrow_left-24px.svg&quot;);width: 70px;height: 90px;background-color: rgba(255,255,255,0.1);background-size: contain;background-repeat: no-repeat;"></button>
                                    <button
                                        class="btn btn-primary" id="button-right" type="button" style="background-image: url(&quot;assets/img/keyboard_arrow_right-24px.svg&quot;);width: 70px;height: 90px;background-color: rgba(255,255,255,0.09);background-repeat: no-repeat;background-size: contain;"></button>
                                </div>
                            </div>
                            <!-- Add $_SESSION match data into discover card for name, age and description -->
                            <div style="margin: 0 -15px 0 -15px;padding: 0 15px 0 15px;background-color: #f7f9fc;border-bottom-left-radius: 20px;border-bottom-right-radius: 20px;">
                                <h1 id="match-name" style="width: auto;"><?php if(isset($_SESSION["match_name"])) {
                                    $user_account_id=$_SESSION["match_id"];
                                    echo "<a href='accountInfo.php?user_account_id=$user_account_id'>" . $_SESSION["match_name"] . "</a>";
                                }?></h1>
                                <h1 id="match-age" style="font-size: 26px;"><?php if(isset($_SESSION["match_age"])) echo $_SESSION["match_age"];?></h1>
                                <p id="match-bio" style="font-size: 14px;"><br><?php if(isset($_SESSION["match_description"])) echo $_SESSION["match_description"];?><br><br></p>
                            </div>
                            <div class="text-center d-block" id="like-dislike-buttons" style="width: 100%;text-align: center;">
                                <form id="dislikeform" action="sendDiscoverToDB.php" method="post">
                                    <Button name="dislike-button" class="btn btn-primary" data-bs-hover-animate="pulse" id="dislike-button" type="submit"><span id="dislike-span" style="background: url(&quot;assets/img/thumb_down-24px.svg&quot;);padding: 2px 12px;"></span></Button>
                                    <button name="like-button" class="btn btn-primary" data-bs-hover-animate="pulse" id="like-button" type="submit"><span style="background: url(&quot;assets/img/thumb_up-24px.svg&quot;);padding: 2px 12px;"></span></button>
                                </form>
                                <button class="btn btn-primary" id="report-user" type="button" data-toggle="modal" data-target="#reportModal" style="color: rgba(255,255,255,0);background-color: rgba(0,123,255,0);background-image: url(&quot;assets/img/report-24px.svg&quot;);width: 30px;height: 30px;background-size: cover;background-repeat: no-repeat;border: none;/*float: left;*/margin-top: 12px;/*margin-left: 12px;*/" alt="'Report'"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-xl-9 col-md-8 col-xs-12 h-100" id="discover-out-of-matches" style="background-color: rgba(221,221,221,0.19);padding: 0 !important;height: 100%;<?php if (!$out_of_matches) echo " display:none;"; ?>">
                <div class="row h-100 justify-content-center align-items-center" style="margin: 0;">
                    <p>No people in your area.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Input Modal -> Gets report comment from user about match and sends it to report field in Report table -->
    <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLongTitle">Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="sendDiscoverToDB.php" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <textarea name="reportText" class="form-control" id="reportTextArea" rows="5" placeholder="Report Message..." maxlength="200" style="resize: none; overflow:hidden;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button name="report-button" type="submit" class="btn btn-danger">Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/bootstrap-slider.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
    <script src="assets/js/Image-slider-carousel-With-arrow-buttons.js"></script>
    <script src="assets/js/Profile-Edit-Form.js"></script>
    <script src="assets/js/Range-selector---slider.js"></script>

</body>
</html>