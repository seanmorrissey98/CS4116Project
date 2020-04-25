<?php
// Include config file
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once "DBFunctions.php";

// Twig for templating matched cards. Stored in templates directory
require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

// Initialize the session
session_start();

// For Testing
// $_SESSION["user_id"] = 53;

// Initialize array for database pull
$discoverPerson = array();
$matched_data = array();

$discoverPerson = getDiscoverPeople($_SESSION['user_id']);
$matched_data = getMatches($_SESSION['user_id']);

$out_of_matches = sizeof($discoverPerson) === 0;
$no_matches = sizeof($matched_data) === 0;

$matched_cards = $twig->render('matched_users_template.html.twig', ['matched_data' => $matched_data]);

// Check the array is not empty
if (!$out_of_matches) {
    $nextmatchDate = $discoverPerson[0];

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
    $_SESSION["match_photo"] = $nextmatchDate['Photo'];
    $user_account_id = $_SESSION["match_id"];
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
    <script src="https://kit.fontawesome.com/6a9548b3b1.js" crossorigin="anonymous"></script>
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
    <link rel="stylesheet" href="assets/css/checkbox-nice.css">
    <link rel="stylesheet" href="assets/css/scrollbar.css">
</head>

<body style="background-color: rgb(255,255,255);height: 100% !important;">
<nav class="navbar navbar-light navbar-expand-md navigation-clean" id="discover-navbar">
    <div class="container-fluid">
        <button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon" style="opacity: 1;filter: brightness(200%) hue-rotate(0deg) invert(100%);"></span></button>
        <div class="collapse navbar-collapse" id="navcol-1">
            <ul class="nav navbar-nav ml-auto d-flex justify-content-between" id="discover-nav" style="padding: 0 80px;">
                <li class="nav-item" role="presentation" id="messaging-link">
                    <a class="nav-link" id="messaging-nav" href="messaging.php" style="color: #ffffff;">Messages</a>
                </li>
                <li class="nav-item" role="presentation" id="discover-link-1"><a class="nav-link active" id="discover-nav-1" href="discover.php" style="color: #ffffff;">Discover</a>
                </li>
                <li class="nav-item" role="presentation"><a class="nav-link" href="accountInfo.php" style="color: #ffffff;">Profile</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item" role="presentation"><a id="advanced-search" class="nav-link" href="#" style="color: #ffffff;"> <i class="fas fa-search"></i></a></li>
                <li class="nav-item" role="presentation"><a id="hide-advanced-search" class="nav-link" href="#" style="color: #ffffff; display: none;"> <i class="fas fa-search-minus"></i></a></li>
            </ul>
        </div>
    </div>
</nav>
<div id="new-people-section" class="container-fluid" style="background-color: rgba(223,232,238,0);padding: 0;height: calc(100% - 84px);">
    <div class="row" style="margin-right: 0;height: 100%;">
        <div class="col col-xl-3 col-md-4" id="messaging-sidebar" style="padding: 0;">
            <div id="messaging" class="container-fluid" style="padding-right: 0;">
                <header class="section-header">
                    <p style="margin: -0.7rem;">Matches</p>
                </header>
                <div class="container-fluid sidebar-scrollable">
                    <!-- Templating -->
                    <?php echo $matched_cards; ?>
                    <div class="row h-100 justify-content-center align-items-center" style="<?php if (!$no_matches) echo " display:none;"; ?>">
                        <p>Start liking to get matches!</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col" id="discover-main" style="background-color: rgba(221,221,221,0.19);padding: 0 !important;height: 100%;<?php if ($out_of_matches) echo " display:none;"; ?>">
            <div class="container row justify-content-center" id="discover-people" style="height: auto; padding-bottom: 0; margin: 0; padding-top: 50px;">
                <div id="discover-wrapper" class="discover-people-sizing" style="max-width: 500px;">
                    <div style="border: 2px solid #6e6f70; border-radius: 20px; overflow: hidden; box-shadow: 3px 4px #38373726">
                        <div class="" id="match-photos">
                            <div class="col" id="image" style="padding: 0;">
                                <div class="image-wrapper-tall">
                                    <img id="match-photo" src="user_images/<?php echo $_SESSION["match_photo"] ?>" width="320px" style="float: none !important;">
                                </div>
                                <button class="btn btn-primary" id="button-left" type="button"
                                        style="background-image: url(&quot;assets/img/keyboard_arrow_left-24px.svg&quot;);width: 70px;height: 90px;background-color: rgba(255,255,255,0.1);background-size: contain;background-repeat: no-repeat;"></button>
                                <button class="btn btn-primary" id="button-right" type="button"
                                        style="background-image: url(&quot;assets/img/keyboard_arrow_right-24px.svg&quot;);width: 70px;height: 90px;background-color: rgba(255,255,255,0.09);background-repeat: no-repeat;background-size: contain;"></button>
                            </div>
                        </div>
                        <!-- Add $_SESSION match data into discover card for name, age and description -->
                        <div style="background-color: #f7f9fc; padding: 8px; margin-bottom: -15px;">
                            <h3 id="match-name"><?php if (isset($_SESSION["match_name"])) echo "<a href='accountInfo.php?user_account_id=$user_account_id'>" . $_SESSION["match_name"] . "</a>" ?></h3>
                            <h4 id="match-age"><?php if (isset($_SESSION["match_age"])) echo $_SESSION["match_age"]; ?></h4>
                            <p id="match-bio" style="font-size: 14px;">
                                <br><?php if (isset($_SESSION["match_description"])) echo $_SESSION["match_description"]; ?>
                                <br><br></p>
                        </div>
                    </div>
                    <div class="text-center d-block" id="like-dislike-buttons" style="width: 100%;text-align: center; margin-top: 20px;">
                        <form id="dislikeform" action="sendDiscoverToDB.php" method="post">
                            <Button name="dislike-button" class="btn btn-primary" data-bs-hover-animate="pulse" id="dislike-button" type="submit"><span id="dislike-span" style="background: url(&quot;assets/img/thumb_down-24px.svg&quot;);padding: 2px 12px;"></span></Button>
                            <button name="like-button" class="btn btn-primary" data-bs-hover-animate="pulse" id="like-button" type="submit"><span style="background: url(&quot;assets/img/thumb_up-24px.svg&quot;);padding: 2px 12px;"></span></button>
                            <input type="hidden" id="match_id_hidden" name="match_id" value="<?php if (isset($_SESSION['match_id'])) echo $_SESSION['match_id']; ?>"/>
                        </form>
                        <button class="btn btn-primary" id="report-user" type="button" data-toggle="modal" data-target="#reportModal"
                                style="color: rgba(255,255,255,0);background-color: rgba(0,123,255,0);background-image: url(&quot;assets/img/report-24px.svg&quot;);width: 30px;height: 30px;background-size: cover;background-repeat: no-repeat;border: none;/*float: left;*/margin-top: 12px;/*margin-left: 12px;*/"></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col h-100" id="discover-out-of-matches" style="background-color: rgba(221,221,221,0.19);padding: 0 !important;height: 100%;<?php if (!$out_of_matches) echo " display:none;"; ?>">
            <div class="row h-100 justify-content-center align-items-center" style="margin: 0; padding-top: 50px;">
                <p>No people in your area.</p>
            </div>
        </div>
        <div class="search-sidebar col-xs-0" id="search-sidebar" style="padding: 0; margin-right: 1px;">
            <div id="searching" class="container-fluid" style="padding: 0;">
                <header class="section-header">
                    <p>Advanced Search</p>
                </header>
                <div class="container-fluid sidebar-scrollable">
                    <form action="sendSearchToDB.php" method="post">
                        <div style="width:100%">
                            <p><strong>Age</strong></p>
                            <b>18</b> &nbsp;&nbsp; <input name="age" id="age-advanced-search" type="text" class="span2" value="" data-slider-min="18" data-slider-max="100" data-slider-step="1" data-slider-value="[18,40]"/>&nbsp;&nbsp; <b>100</b></div>
                        <hr>
                        <p><strong>Gender</strong></p>
                        <label class="check-container">Male
                            <input type="checkbox" name="male">
                            <span class="check-checkmark"></span>
                        </label>

                        <label class="check-container">Female
                            <input type="checkbox" name="female">
                            <span class="check-checkmark"></span>
                        </label>

                        <label class="check-container">Other
                            <input type="checkbox" name="other">
                            <span class="check-checkmark"></span>
                        </label>
                        <hr>
                        <div class="radio">
                            <p><strong>Drinker</strong></p>
                            <label class="check-container">Drinker
                                <input type="radio" name="drinker-radio" value="drinker">
                                <span class="radio-checkmark"></span>
                            </label>
                            <label class="check-container">Non-Drinker
                                <input type="radio" name="drinker-radio" value="non-drinker">
                                <span class="radio-checkmark"></span>
                            </label>
                            <label class="check-container">Any
                                <input type="radio" name="drinker-radio" value="any">
                                <span class="radio-checkmark"></span>
                            </label>
                        </div>
                        <div class="radio">
                            <p><strong>Smoker</strong></p>
                            <label class="check-container">Smoker
                                <input type="radio" name="smoker-radio" value="1">
                                <span class="radio-checkmark"></span>
                            </label>
                            <label class="check-container">Non-Smoker
                                <input type="radio" name="smoker-radio" value="0">
                                <span class="radio-checkmark"></span>
                            </label>
                            <label class="check-container">Any
                                <input type="radio" name="smoker-radio" value="any">
                                <span class="radio-checkmark"></span>
                            </label>
                        </div>
                        <hr>
                        <p><strong>Hobbies</strong></p>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="check-container">Soccer
                                    <input type="checkbox" name="Soccer">
                                    <span class="check-checkmark"></span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="check-container">Sport
                                    <input type="checkbox" name="Sport">
                                    <span class="check-checkmark"></span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="check-container">Film
                                    <input type="checkbox" name="Film">
                                    <span class="check-checkmark"></span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="check-container">Painting
                                    <input type="checkbox" name="Painting">
                                    <span class="check-checkmark"></span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="check-container">Photography
                                    <input type="checkbox" name="Photography">
                                    <span class="check-checkmark"></span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="check-container">Dance
                                    <input type="checkbox" name="Dance">
                                    <span class="check-checkmark"></span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="check-container">Gardening
                                    <input type="checkbox" name="Gardening">
                                    <span class="check-checkmark"></span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="check-container">Exercise
                                    <input type="checkbox" name="Exercise">
                                    <span class="check-checkmark"></span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="check-container">Cooking
                                    <input type="checkbox" name="Cooking">
                                    <span class="check-checkmark"></span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="check-container">Video Games
                                    <input type="checkbox" name="Video Games">
                                    <span class="check-checkmark"></span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="check-container">Shopping
                                    <input type="checkbox" name="Shopping">
                                    <span class="check-checkmark"></span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="check-container">Music
                                    <input type="checkbox" name="Music">
                                    <span class="check-checkmark"></span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="check-container">Cycling
                                    <input type="checkbox" name="Cycling">
                                    <span class="check-checkmark"></span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="check-container">Programming
                                    <input type="checkbox" name="Programming">
                                    <span class="check-checkmark"></span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="check-container">Archery
                                    <input type="checkbox" name="Archery">
                                    <span class="check-checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <button class="btn btn-primary float-right" type="submit">Search <i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col col-xl-3 col-md-4" style="padding: 0; margin-right: 1px; margin-left: -1px; <?php if (!isset($_SESSION['advanced_search_result'])) echo " display:none;"; ?>" id="search-results-sidebar">
            <div id="messaging" class="container-fluid" style="padding: 0;">
                <header id="message-header" style="background-color: #f7f9fc;">
                    <a id="close-search-results" class="nav-link" href="endSearch.php" style="float: left; margin-top: -4px; color: black;"> <i class="far fa-times-circle"></i></a>
                    <p>Search Results</p>
                </header>
                <div class="container-fluid sidebar-scrollable">
                    <div class="row">
                        <!-- Templating -->
                        <?php if (isset($_SESSION['advanced_search_result'])) echo $twig->render('searched_users_template.html.twig', ['matched_data' => $_SESSION['advanced_search_result']]); ?>
                    </div>
                    <div class="row h-100 justify-content-center align-items-center" style="<?php if (!empty($_SESSION['advanced_search_result'])) echo " display:none;"; ?>">
                        <p>No results found!</p>
                    </div>
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
                    <input type="hidden" id="report_match_id_hidden" name="match_id" value="<?php if (isset($_SESSION['match_id'])) echo $_SESSION['match_id']; ?>"/>
                </form>
            </div>
        </div>
    </div>
    <!--  Removes empty advanced search array from $_SESSION after displayed -->
    <?php
    if (empty($_SESSION['advanced_search_result'])) {
        unset($_SESSION['advanced_search_result']);
    }
    ?>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/bootstrap-slider.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
    <script src="assets/js/Image-slider-carousel-With-arrow-buttons.js"></script>
    <script src="assets/js/Profile-Edit-Form.js"></script>
    <script src="assets/js/Range-selector---slider.js"></script>
    <script src="assets/js/discover.js"></script>

</body>
</html>