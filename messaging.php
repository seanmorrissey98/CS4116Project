<?php
// Include config file

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once "DBFunctions.php";
require_once "TwigFunctions.php";

// Twig for templating matched cards. Stored in templates directory
require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

// Initialize the session
session_start();

// Initialize chat array for database pull
$chats_data = '';

// Get all chat data for the 'Messages' Side Panel
$chats_data = getChats($_SESSION['user_id']);

$chat_id_1 = array();

// Find select chat in array of chats
if (sizeof($chats_data) > 0 && isset($_POST['match_user_id'])) {
    $match_user_id = $_POST['match_user_id'];

    foreach ($chats_data as $chat) {
        if ($chat['user_id_receiver'] === $match_user_id) {
            $chat_id_1 = $chat;
            break;
        }
    }
}

$first_chat = '';

if (sizeof($chats_data) > 0) {
    if (sizeof($chat_id_1) === 0) if (sizeof($chat_id_1) === 0) $chat_id_1 = $chats_data[0];
    $first_messages = getMessagesForChat($chat_id_1['chat_id']);
    $first_messages_html = getTwigMessages($first_messages, $_SESSION['user_id']);
}

// Send Chat data to twig to render into cards
$chat_cards = $twig->render('chats_users_template.html.twig', ['chats_data' => $chats_data]);

?>

<!DOCTYPE html>
<html>

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
    <link rel="stylesheet" href="assets/css/checkbox-nice.css">
    <link rel="stylesheet" href="assets/css/scrollbar.css">
</head>

<body style="background-color: rgb(255,255,255);height: 100% !important;">
<nav class="navbar navbar-light navbar-expand-md navigation-clean" id="discover-navbar">
    <div class="container">
        <button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon" style="opacity: 1;filter: brightness(200%) hue-rotate(0deg) invert(100%);"></span></button>
        <div class="collapse navbar-collapse" id="navcol-1">
            <ul class="nav navbar-nav ml-auto d-flex justify-content-between" id="discover-nav">
                <li class="nav-item" role="presentation" id="messages-link"><a class="nav-link active" href="messaging.php" style="color: #ffffff;">Messages</a></li>
                <li class="nav-item" role="presentation" id="discover-link"><a class="nav-link" id="discover-nav" href="discover.php" style="font-size: 20px;color: #ffffff;">Discover</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" href="accountInfo.php" style="color: #ffffff;">Profile</a></li>
            </ul>
        </div>
    </div>
</nav>
<div id="new-people-section" class="container-fluid" style="background-color: rgba(223,232,238,0);padding: 0;height: calc(100% - 78px);">
    <div class="row" style="margin-right: 0;height: 100%;">
        <div class="col col-lg-3 col-md-4" id="messaging-sidebar" style="padding: 0;">
            <div id="messaging" class="container-fluid" style="padding-right: 0;">
                <header class="section-header"><p>Chats</p></header>
                <div id="messages" class="container-fluid" style="height: calc(100vh - 135px);">
                    <div class="sidebar-scrollable" style="margin: -15px">
                        <!-- Templating -->
                        <?php echo $chat_cards; ?>
                    </div>
                    <!--<div id="search-users"><input class="form-control-lg" type="text" id="search" placeholder="Name"><i class="material-icons submit" id="search-icon">search</i></div>-->
                </div>
            </div>
        </div>
        <div class="col col-lg-9 col-md-8 col-xs-12" id="messaging-main" style="background-color: #ffffff;padding: 0 !important; height: calc(100vh - 84px);">
            <header class="section-header">
                <p id="header-name"><?php if (sizeof($chat_id_1) > 0) echo $chat_id_1['first_name']; ?></p>
            </header>
            <section id="message-section" class="message-section container-fluid chats-sidebar-scrollable">
                <?php if (sizeof($chats_data) > 0) {
                    if (!empty($first_messages_html)) echo $first_messages_html; else echo '<p id="empty-chat-message">Say Hi to ' . $chat_id_1['first_name'] . '!</p>';
                } ?>
            </section>
            <footer id="messaging-footer">
                <form id="send-message" action="#">
                    <span id="new-message-send-icon" class="new-message-send-icon">
                        <i class="fa fa-send" id="send-message"></i>
                    </span>
                    <input type="text" id="new-message" autocomplete="off" placeholder="Type a message ...">
                </form>
            </footer>
        </div>
    </div>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/bs-init.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/bootstrap-slider.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
<!--<script src="assets/js/Image-slider-carousel-With-arrow-buttons.js"></script>-->
<script src="assets/js/Profile-Edit-Form.js"></script>
<script src="assets/js/Range-selector---slider.js"></script>
<script src="assets/js/messaging.js"></script>
<?php if (sizeof($chat_id_1) > 0) {
    echo "<script>currChat = " . json_encode($chat_id_1) . ";</script>";
} ?>
</body>

</html>