<?php
session_start();
if (!isset($_SESSION["adminLoggedIn"])) {
    header("Location: login.php");
    exit;
}


function logout()
{
    $_SESSION = array();
    session_destroy();
    header("Location: login.php");
}
function getTotalUserCount() {
    include "connection.php";
    $sql = "SELECT * FROM User";
    $result = mysqli_query($con, $sql);
    echo mysqli_num_rows($result);
}

function getTotalReportedUserCount() {
    include "connection.php";
    $sql = "SELECT * FROM Reports";
    $result = mysqli_query($con, $sql);
    echo mysqli_num_rows($result);
}

function getTotalBannedUserCount() {
    include "connection.php";
    $sql = "SELECT * FROM Banned";
    $result = mysqli_query($con, $sql);
    echo mysqli_num_rows($result);
}

function getTotalConnectionCount() {
    include "connection.php";
    $sql = "SELECT * FROM Connection";
    $result = mysqli_query($con, $sql);
    echo mysqli_num_rows($result);
}
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
</head>

<body>
    <div>
        <div class="header-blue" style="background-color: rgb(195,12,23);">
            <nav class="navbar navbar-light navbar-expand-md navigation-clean-search">
                <div class="container-fluid"><a class="navbar-brand" href="adminDashboard.php">Limerick Lovers ADMIN</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse"
                         id="navcol-1">
                        <ul class="nav navbar-nav">
                            <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#">Dropdown </a>
                                <div class="dropdown-menu" role="menu"><a class="dropdown-item" role="presentation" href="#">First Item</a><a class="dropdown-item" role="presentation" href="#">Second Item</a><a class="dropdown-item" role="presentation" href="#">Third Item</a></div>
                            </li>
                        </ul>
                        <form class="form-inline mr-auto" target="_self">
                        </form><span class="navbar-text"> <a class="login" href="?logout=true">Log Out</a>
                    <?php
                    if (isset($_GET["logout"])) {
                        logout();
                    }
                    ?>
                    </span></div>
                </div>
            </nav>
        </div>
    </div>
    <div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title text-center">Admin Dashboard</h2>
                            <hr>
                            <p class="card-text"><h4>Welcome <?php echo $_SESSION["first_name"] . "</h4>" . "\t" . $_SESSION["email"];  ?></h4></p>
                            <hr>
                            <a class="btn btn-outline-dark" href="messaging.php">Inbox</a></div>
                    </div>
                </div>
            </div>
            <div class="row" >
                <div class="col">
                    <div class="card">
                        <div class="card-body text-center">
                            <h2 class="card-title">Total Users</h2>
                            <hr>
                            <p class="card-text"><h3><?php getTotalUserCount()?></h3></p><a class="btn btn-outline-dark" href="userList.php">See More</a><a class="card-link" href="#"></a></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card" >
                        <div class="card-body text-center">
                            <h2 class="card-title">Banned Users</h2>
                            <hr>
                            <p class="card-text"><h3><?php getTotalBannedUserCount()?></h3></p><a class="btn btn-outline-dark" href="bannedUserList.php">See More</a><a class="card-link" href="#"></a></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-body text-center">
                            <h2 class="card-title">Reports</h2>
                            <hr>
                            <p class="card-text"><h3><?php getTotalReportedUserCount()?></h3></p><a class="btn btn-outline-dark" href="reportedList.php">See More</a><a class="card-link" href="#"></a></div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-body text-center">
                            <h2 class="card-title">Connections</h2>
                            <hr>
                            <p class="card-text"><h3><?php getTotalConnectionCount()?></h3></p><a class="btn btn-outline-dark" href="connectionList.php">See More</a><a class="card-link" href="#"></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-basic">
        <footer>
            <div class="social"><a href="#"><i class="icon ion-social-instagram"></i></a><a href="#"><i class="icon ion-social-snapchat"></i></a><a href="#"><i class="icon ion-social-twitter"></i></a><a href="#"><i class="icon ion-social-facebook"></i></a></div>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="#">Home</a></li>
                <li class="list-inline-item"><a href="#">Services</a></li>
                <li class="list-inline-item"><a href="#">About</a></li>
                <li class="list-inline-item"><a href="#">Terms</a></li>
                <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
            </ul>
            <p class="copyright">Company Name © 2017</p>
        </footer>
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