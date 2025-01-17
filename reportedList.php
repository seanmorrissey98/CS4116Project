<?php
session_start();
if (!isset($_SESSION["adminLoggedIn"])) {
    header("Location: login.php");
    exit;
}
function populateReportTable() {
    // include "localDBConnection.php";
    include "connection.php";
    $sql = "SELECT Reports.Report_id, Reports.user_id, Reports.reported_user_id, Reports.date, Reports.reason, User.first_name, User.last_name 
    FROM Reports  JOIN 
    User ON Reports.reported_user_id = User.user_id";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $user_account_id = $row["user_id"];
            $reported_user_account_id = $row["reported_user_id"];
            $report_id=$row['Report_id'];
            echo "<tr>
            <td><a href='accountInfo.php?user_account_id=$user_account_id'>" . $row["user_id"] . "</a></td>
            <td><a href='accountInfo.php?user_account_id=$reported_user_account_id'>" . $row["reported_user_id"] . "</a></td>
            <td><a href='accountInfo.php?user_account_id=$reported_user_account_id'>" . $row["first_name"] . " " . $row["last_name"] . "</a></td>
            <td><a href='accountInfo.php?user_account_id=$reported_user_account_id'>" . $row["date"] . "</a></td>
            <td><a href='accountInfo.php?user_account_id=$reported_user_account_id'>" . $row["reason"] . "</a></td>
            <td><a class='btn btn-outline-dark btn-lg' href='banUser.php?banUser=$report_id&userId=$reported_user_account_id'>Ban</button></td>
            </tr>";
        }
        
    }
} 

function searchReportTable() {
    $search = $_GET["reportSearch"];
    include "connection.php";
    // include "localDBConnection.php";
    $sql = "SELECT Reports.Report_id, Reports.user_id, Reports.reported_user_id, Reports.date, Reports.reason, User.first_name, User.last_name 
    FROM Reports JOIN User ON Reports.reported_user_id = User.user_id
    WHERE Reports.reason LIKE '%$search%' OR User.first_name LIKE '%$search%' OR User.last_name LIKE '%$search%'";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $user_account_id = $row["user_id"];
        $reported_user_account_id = $row["reported_user_id"];
        echo "<tr>
        <td><a href='accountInfo.php?user_account_id=$user_account_id'>" . $row["user_id"] . "</a></td>
        <td><a href='accountInfo.php?user_account_id=$reported_user_account_id'>" . $row["reported_user_id"] . "</a></td>
        <td><a href='accountInfo.php?user_account_id=$reported_user_account_id'>" . $row["first_name"] . " " . $row["last_name"] . "</a></td>
        <td><a href='accountInfo.php?user_account_id=$reported_user_account_id'>" . $row["date"] . "</a></td>
        <td><a href='accountInfo.php?user_account_id=$reported_user_account_id'>" . $row["reason"] . "</a></td>
        <td><a class='btn btn-outline-dark btn-lg' href='banUser.php?banUser=$report_id&userId=$reported_user_account_id'>Ban</button></td>
        </tr>";
    }
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
        <?php include "adminHeader.php";?>
    </div>
    <div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="card-title text-center">Reported User List</h1>
                            <hr>  
                            <form class="form-inline mr-auto" action="reportedList.php" method="get">
                                    <input class="form-control mr-sm-2" type="search" name="reportSearch" placeholder="Search Reported Users">
                                    <input class="btn btn-dark" type="submit" value="Search">


                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Reported By</th>
                                    <th>Reported User ID</th>
                                    <th>Reported User Name</th>
                                    <th>Report date</th>
                                    <th>Report Reason</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php                            
                                    if (isset($_GET["reportSearch"])) {
                                        searchReportTable($_GET["reportSearch"]);
                                    } else {
                                        populateReportTable();                                    
                                    }
                                ?>
                            </tbody>
                        </table>
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