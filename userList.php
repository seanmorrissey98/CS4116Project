<?php 
session_start();
include "functions.php";
if (!isset($_SESSION["adminLoggedIn"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['delete']) && isset($_GET['user_id'])) {
    deleteUserFromDB($_GET['user_id']);
    header("location: userList.php");
}

function deleteUserFromDB($id) {
	include "connection.php";
	// DELETE FROM BANNED
	$sqlDel = "DELETE FROM `Banned` WHERE `Banned`.`user_id` = $id";
    if (mysqli_query($con, $sqlDel)) {
	}
	// DELETE FROM CHAT
	$sqlDel = "DELETE FROM `Chat` WHERE `Chat`.`user_id_sender` = $id OR `Chat`.`user_id_receiver` = $id";
    if (mysqli_query($con, $sqlDel)) {
	}

	// DELETE FROM DISLIKE
	$sqlDel = "DELETE FROM `Dislike` WHERE `Dislike`.`user_id` = $id OR `Dislike`.`disliked_user_id` = $id";
    if (mysqli_query($con, $sqlDel)) {
	}

	// DELETE FROM Interests
	$sqlDel = "DELETE FROM `Interests` WHERE `Interests`.`user_id` = $id";
    if (mysqli_query($con, $sqlDel)) {
	}

	// DELETE FROM Likes
	$sqlDel = "DELETE FROM `Likes` WHERE `Likes`.`user_id` = $id OR `Likes`.`liked_user_id` = $id";
    if (mysqli_query($con, $sqlDel)) {
	}

	// DELETE FROM Messages
	$sqlDel = "DELETE FROM `Messages` WHERE `Messages`.`user_id_sender` = $id OR `Messages`.`user_id_receiver` = $id";
    if (mysqli_query($con, $sqlDel)) {
	}

	// DELETE FROM Profile
	$sqlDel = "DELETE FROM `Profile` WHERE `Profile`.`user_id` = $id";
    if (mysqli_query($con, $sqlDel)) {
	}

	// DELETE FROM Reports
	$sqlDel = "DELETE FROM `Reports` WHERE `Reports`.`reported_user_id` = $id";
    if (mysqli_query($con, $sqlDel)) {
	}

	// DELETE FROM User
	$sqlDel = "DELETE FROM `User` WHERE `User`.`user_id` = $id";
    if (mysqli_query($con, $sqlDel)) {
	}
}



function populateUserTable() {  
    // include "localDBConnection.php";
    include "connection.php";
    $sql = "SELECT user_id, email, first_name, last_name, date_joined, user_type from User";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $user_account_id = $row["user_id"];
        if ($row['user_type'] == 'administrator') {
            echo "<tr>
            <td>" . $row["user_id"] . "</td>
            <td>" . $row["email"] . "</td>
            <td>" . $row["first_name"] . "</td>
            <td>" . $row["last_name"] . "</td>
            <td>" . $row["date_joined"] . "</td>
            <td>" . $row["user_type"] . "</td>
            </tr>";
        } else {
            echo "<tr>
        <td><a href='accountInfo.php?user_account_id=$user_account_id'>" . $row["user_id"] . "</a></td>
        <td><a href='accountInfo.php?user_account_id=$user_account_id'>" . $row["email"] . "</a></td>
        <td><a href='accountInfo.php?user_account_id=$user_account_id'>" . $row["first_name"] . "</a></td>
        <td><a href='accountInfo.php?user_account_id=$user_account_id'>" . $row["last_name"] . "</a></td>
        <td>" . $row["date_joined"] . "</td>
        <td>" . $row["user_type"] . "</td>
        <td><a class='btn btn-outline-dark btn-lg' href='userList.php?user_id=$user_account_id&delete=true'>Delete User</button></td>
        </tr>";



        }
    }
}



}
function searchUserTable() {
    $search = $_GET["userSearch"];
    include "connection.php";
    // include "localDBConnection.php";
    $sql = "SELECT user_id, email, first_name, last_name, date_joined, user_type from User WHERE email LIKE '%$search%' OR first_name LIKE '%$search%' OR last_name LIKE '%$search%'";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $user_account_id = $row["user_id"];
        if ($row['user_type'] == 'administrator') {
            echo "<tr>
            <td>" . $row["user_id"] . "</td>
            <td>" . $row["email"] . "</td>
            <td>" . $row["first_name"] . "</td>
            <td>" . $row["last_name"] . "</td>
            <td>" . $row["date_joined"] . "</td>
            <td>" . $row["user_type"] . "</td>
            </tr>";
        } else {
            echo "<tr>
        <td><a href='accountInfo.php?user_account_id=$user_account_id'>" . $row["user_id"] . "</a></td>
        <td><a href='accountInfo.php?user_account_id=$user_account_id'>" . $row["email"] . "</a></td>
        <td><a href='accountInfo.php?user_account_id=$user_account_id'>" . $row["first_name"] . "</a></td>
        <td><a href='accountInfo.php?user_account_id=$user_account_id'>" . $row["last_name"] . "</a></td>
        <td>" . $row["date_joined"] . "</td>
        <td>" . $row["user_type"] . "</td>
        <td><a class='btn btn-outline-dark btn-lg' href=''>Delete User</button></td>
        </tr>";



        }
        
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
                            <h1 class="card-title text-center">User List</h1>
                            <hr>  
                            <form class="form-inline mr-auto" action="userList.php" method="get">
                                    <input class="form-control mr-sm-2" type="search" name="userSearch" placeholder="Search Users">
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
                            <thead class= "thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Date Joined</th>
                                    <th>User Type</th>
                                    <th></th>
                                </tr>

                            </thead>
                            <tbody>
                            <?php                            
                                if (isset($_GET["userSearch"])) {
                                    searchUserTable($_GET["userSearch"]);
                                } else {
                                    populateUserTable();                                    
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