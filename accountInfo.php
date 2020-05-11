<?php
session_start();

if (isset($_SESSION["adminLoggedIn"]) && $_SESSION["adminLoggedIn"] == true && !isset($_GET["user_account_id"])) {
    header("Location: adminDashboard.php");
}

if (isset($_GET['unban']) && isset($_GET['user_account_id'])) {
    unbanUser($_GET['user_account_id']);
}

if (isset($_SESSION["adminLoggedIn"]) && $_SESSION["adminLoggedIn"] == true && isset($_GET["user_account_id"]) && isset($_GET['delete'])) {
    deleteUserFromDB($_GET['user_account_id']);
    header("location: adminDashboard.php");
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

function unbanUser($id) {
    include "connection.php";
    $sqlDel = "DELETE FROM `Banned` WHERE `Banned`.`user_id` = $id";
    if (mysqli_query($con, $sqlDel)) {
        header("location: accountInfo.php?user_account_id=$id");
    }
}

function getUserBanStatus($id) {
    include "connection.php";
    $array = array();

    $sql = "SELECT * FROM `Banned` WHERE `Banned`.`user_id` = $id";
    $status = false;
    $result = $con->query($sql) or die($con->error);
    while ($row = $result->fetch_assoc()) {
        $status = true;
    }
    $con->close();

    return $status;
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
    <script>
        function getPref() {
            document.getElementById("labelness").innerHTML = (GENDERPREF.value);

        }
    </script>
</head>

<body>
<?php
require('functions.php');
if (isset($_GET["user_account_id"])) {
    $userType = getUserType($_GET["user_account_id"]);
    if ($userType != 'user') {
        header("location: adminDashboard.php");
    }
    $other_user = true;
    $var_profile_user = $_GET["user_account_id"];
    $usersBio = getUsersBio($var_profile_user);
    $users = getUser($var_profile_user);
    $availInterests = getAvailInterests();
    $genderPref = getUserGenderPreference($var_profile_user);
    $interests = getInterests($var_profile_user);
} else {
    $other_user = false;
    $usersBio = getUsersBio($_SESSION['user_id']);
    $users = getUser($_SESSION['user_id']);
    $availInterests = getAvailInterests();
    $interests = getInterests($_SESSION['user_id']);
    $genderPref = getUserGenderPreference($_SESSION["user_id"]);
}
?>
<?php include('header.php') ?>
<div class="container profile profile-view" id="profile">
    <div class="row">
        <div class="col-md-12 alert-col relative">
            <div class="alert alert-info absolue center" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <span>Profile save with success</span></div>
        </div>
    </div>
    <form method="post">
        <div class="form-row profile-row">
            <div class="col-md-4 relative">
                <div class="avatar" style="height: 250px;">
                    <div class="avatar-bg center" style="background-image: url(
                    <?php
                    if (!$other_user) {
                        $filename = "user_images/" . getUserImageName($_SESSION["user_id"]);
                    } else {
                        $filename = "user_images/" . getUserImageName($var_profile_user);
                    }
                    if (file_exists($filename)) {
                        echo "$filename";
                    } else {
                        echo "&quot;assets/img/2.jpg&quot;";
                    }
                    ?>
                            );"></div>
                </div>
                <h1><?php echo $users['first_name'] . " " . $users['last_name']; ?></h1>

                <div><br></div>
                <div class="form-group"><label><b>Email:</b></label><?php echo "\t" . $users['email'] ?></div>
                <div class="form-group"><label><b>Gender:</b></label><?php echo "\t" . $usersBio['Gender'] ?></div>
                <div class="form-group"><label><b>Age:</b></label><?php echo "\t" . $usersBio['Age'] ?> </div>

            </div>
            <div class="col-md-8">
                <h1>Profile</h1>
                <hr>
                <div></div>
                <div><p><?php echo $usersBio['Description'] ?></p></div>

                <div>
                    <br>
                </div>
                <?php if (!$other_user) {
                    echo '<a class="btn btn-primary form-btn"  role="button" href="interests.php">Edit Hobbies</a>';
                }
                ?>
                <div class="form-row">
                    <div class="col-sm-12 col-md-6"></div>
                    <div class="col-sm-12 col-md-6"></div>
                </div>
                <hr>
                <div class="form-row">
                    <div class="form-row">
                        <div class="table">
                            <table class="table">
                                <thead class="thead-dark">
                                <tr>
                                    <th>Hobbies</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($interests as &$value) {
                                    echo '<tr><td>' . $value["interest_name"] . '</td></tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6"></div>
                    <div class="col-sm-12 col-md-6">
                        <div class="form-group"><label><h3>Gender Preferences: &nbsp</h3></label><label id="labelness"></label></div>
                        <div></div>
                        <?php
                        if (!$other_user) {
                            echo '<div>
                            <div>
							<form name="formid">
								<select id="GENDERPREF" name="GENDERPREF" onchange="getPref()">';
								if($genderPref == "Male")
								{
									echo '<option value="Male">Male</option>
										  <option value="Female">Female</option>
										  <option value="Other" >Other</option>
										</select>
										</form>
										</div>
									</div>';
								}
								if($genderPref == "Female")
								{
									echo '<option value="Female">Female</option>
										  <option value="Male">Male</option>
										  <option value="Other" >Other</option>
										</select>
										</form>
										</div>
									</div>';
								}
								if($genderPref == "Other")
								{
									echo '<option value="Other" >Other</option>
										  <option value="Male">Male</option>
										  <option value="Female">Female</option>
										</select>
										</form>
										</div>
									</div>';
								}
                        } else {
                            echo '<p>' . getUserGenderPreference($var_profile_user) . "</p>";
                        }
                        ?>
                    </div>
                    <?php
                    if (!$other_user) {
                        echo '<div class="col-md-12 content-right"><button class="btn btn-primary form-btn" name="submit" type="submit">SAVE </button><button class="btn btn-danger form-btn" type="reset">CANCEL </button></div>';
                    }
                    if (isset($_SESSION["adminLoggedIn"]) && $_SESSION["adminLoggedIn"] == true) {
                        $banned = getUserBanStatus($var_profile_user);
                        if ($banned == false) {
                            echo '<div class="col-md-12 content-right"><a class="btn btn-primary form-btn" href="banUser.php?userId=' . $var_profile_user . '">Ban User</a></div>';
                        } else {
                            echo '<div class="col-md-12 content-right"><a class="btn btn-primary form-btn" href="accountInfo.php?user_account_id=' . $var_profile_user . '&unban=true">Unban User</a></div>';
                        }
                        echo '<div class="col-md-12 mt-2 content-right"><a class="btn btn-outline-dark form-btn" href="accountInfo.php?user_account_id=' . $var_profile_user . '&delete=true">Delete User</a></div>';

                    }
                    ?>
                </div>
            </div>
        </div>
    </form>
    <?php
    require("connection.php");
    if (isset($_POST['submit'])) {
        $sql = "UPDATE Profile SET Description='$_POST[description]',Age='$_POST[age]',Seeking='$_POST[GENDERPREF]' WHERE user_id=$usersBio[id]";
        $result = $con->query($sql) or die($con->error);
    }
    ?>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/bs-init.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/bootstrap-slider.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
<script src="assets/js/Image-slider-carousel-With-arrow-buttons.js"></script>
<script src="assets/js/Profile-Edit-Form.js"></script>
<script src="assets/js/Range-selector---slider.js"></script>
<script src="https://kit.fontawesome.com/6a9548b3b1.js" crossorigin="anonymous"></script>
<script src="assets/js/advanced-search.js"></script>
</body>

</html>