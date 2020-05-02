<?php
session_start();
include "functions.php";
if (!isset($_SESSION["adminLoggedIn"])) {
    header("Location: login.php");
    exit;
}
if (!isset($_GET['banUser']) && !isset($_GET['userId'])) {
    header("location: adminDashboard.php");
}

$banUserId;

function PopulateWithUserDetails() {
    $arr = getUser($_GET['userId']);
    $user_account_id = $arr["id"];
            echo "<tr>
            <td><a href='accountInfo.php?user_account_id=$user_account_id'>" . $arr["id"] . "</a></td>
            <td><a href='accountInfo.php?user_account_id=$user_account_id'>" . $arr["first_name"] . "</a></td>
            <td><a href='accountInfo.php?user_account_id=$user_account_id'>" . $arr["last_name"]. "</a></td>
            <td><a href='accountInfo.php?user_account_id=$user_account_id'>" . $arr["email"] . "</a></td>
            </tr>";
}


function populateBanUser() {
    $report_id = $_GET['banUser'];
    // include "localDBConnection.php";
    include "connection.php";
    $sql = "SELECT Reports.Report_id, Reports.user_id, Reports.reported_user_id, Reports.date, Reports.reason, User.first_name, User.last_name 
    FROM Reports  JOIN 
    User ON Reports.reported_user_id = User.user_id
    WHERE Reports.Report_id = $report_id";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $user_account_id = $row["user_id"];
            $reported_user_account_id = $row["reported_user_id"];
            $report_id=$row['Report_id'];
            $banUserId = $reported_user_account_id;
            echo "<tr>
            <td><a href='accountInfo.php?user_account_id=$user_account_id'>" . $row["user_id"] . "</a></td>
            <td><a href='accountInfo.php?user_account_id=$reported_user_account_id'>" . $row["reported_user_id"] . "</a></td>
            <td><a href='accountInfo.php?user_account_id=$reported_user_account_id'>" . $row["first_name"] . " " . $row["last_name"] . "</a></td>
            <td><a href='accountInfo.php?user_account_id=$reported_user_account_id'>" . $row["date"] . "</a></td>
            <td><a href='accountInfo.php?user_account_id=$reported_user_account_id'>" . $row["reason"] . "</a></td>
            </tr>";
        }
        
    }
} 


if (isset($_POST['days']) && $_POST['days'] != '' && isset($_POST['reason']) && $_POST['reason'] != '') {
    banUserInDB();
}

function banUserInDB() {
    include "connection.php";
    if (isset($_GET['banUser'])) {
        $report_id = $_GET['banUser'];
        // include "localDBConnection.php";
        include "connection.php";
        $sql = "SELECT reported_user_id
        FROM Reports WHERE Reports.Report_id = $report_id";
        $result = mysqli_query($con, $sql);
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $reported_user_account_id = $row["reported_user_id"];
            }
        }
    } else {
        $reported_user_account_id = $_GET['userId'];
    }


    $currentId = $_SESSION['user_id'];


    $timestamp = date('Y-m-d H:i:s');
    $reason = $_POST['reason'];
    $duration = $_POST['days'];
    // $eDate = date('Y-m-d H:i:s', strtotime($timestamp . '+ '.$duration.'days'));
    $sql = "INSERT INTO `Banned`(`user_id`, `banned_by`, `date`, `reason`, `duration`) 
    VALUES ('$reported_user_account_id','$currentId','$timestamp','$reason', '$duration')";
    if (mysqli_query($con, $sql)) {
    }
    $sqlDel = "DELETE FROM `Reports` WHERE `Reports`.`reported_user_id` = $reported_user_account_id";
    if (mysqli_query($con, $sqlDel)) {
        header("location: adminDashboard.php");
    }
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
    <div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="card-title text-center">Ban User</h1>
                            <hr>  
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?php
                        if (isset($_GET['banUser'])) {

                        
                        echo '<div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Reported By</th>
                                        <th>Reported User ID</th>
                                        <th>Reported User Name</th>
                                        <th>Report date</th>
                                        <th>Report Reason</th>
                                    </tr>
                                </thead>
                                <tbody>';               
                                    populateBanUser();           
                                echo '</tbody>
                            </table>
                        </div>';
                        }
                        else {
                            echo '<div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>';               
                                    PopulateWithUserDetails();        
                                echo '</tbody>
                            </table>
                        </div>';
                        }
                    ?>
                    <div class="form-group">
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="daysBan">Ban Length (Days)</label>
                                <input class="form-control" type="number" id="daysBan" name="days" value="">
                            </div>
                            <div class="form-group">
                                <label for="banReason">Ban Reason</label>
                                <input class="form-control" type="text" id="banReason" name="reason" value="">
                            </div>
                            <div class="form-group"><input class="btn btn-dark btn-block" type="submit" value="Ban User" name="submitted"></div></form>
                        </form>
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