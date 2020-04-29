<?php
session_start();
if (isset($_SESSION["adminLoggedIn"]) && $_SESSION["adminLoggedIn"] == true && !isset($_GET["user_account_id"])) {
    header("Location: adminDashboard.php");
}
function logout()
{
    $_SESSION = array();
    session_destroy();
    header("Location: index.html");
    exit;
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
	function getPref(){
		document.getElementById("labelness").innerHTML=(GENDERPREF.value);
		
	}
	</script>
</head>

<body>
	<?php
require('functions.php');
if (isset($_GET["user_account_id"])) {
$other_user=true;    
$var_profile_user=$_GET["user_account_id"];
    $usersBio = getUsersBio($var_profile_user);
    $users=getUser($var_profile_user);
    $availInterests=getAvailInterests();
    $genderPref = getUserGenderPreference($var_profile_user);
    $interests=getInterests($var_profile_user);
} else {
    $other_user=false;
$usersBio=getUsersBio($_SESSION['user_id']);
$users=getUser($_SESSION['user_id']);
$availInterests=getAvailInterests();
$interests=getInterests($_SESSION['user_id']);
$genderPref=getUserGenderPreference($_SESSION["user_id"]);
}
// <a class="navbar-brand" href="discover.php">Limerick Lovers</a>
?>
    <div class="header-blue" style="background-color: rgb(195,12,23);">
        <nav class="navbar navbar-light navbar-expand-md navigation-clean-search">
            <div class="container-fluid">
            <?php 
                if (isset($_SESSION["adminLoggedIn"]) && $_SESSION["adminLoggedIn"] == true) {
                    echo "<a class='navbar-brand' href='adminDashboard.php'>Limerick Lovers ADMIN</a>";
                } else {
                    echo "<a class='navbar-brand' href='discover.php'>Limerick Lovers</a>";
                }
            ?>
                <button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navcol-1">
                    <ul class="nav navbar-nav">
                        <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#">More Links </a>
                            <div class="dropdown-menu" role="menu"><a class="dropdown-item" role="presentation" href="discover.php">Discover</a><a class="dropdown-item" role="presentation" href="interests.php">My Interests</a><a class="dropdown-item" role="presentation"
                                                                                                                                                                                                                                     href="messaging.php">Messages</a></div>
                        </li>
                    </ul>
                    <form class="form-inline mr-auto" target="_self"></form>
                    <span class="navbar-text"> <a class="login" href="?logout=true">Log Out</a>
					<?php
                    if (isset($_GET["logout"])) {
                        logout();
                    }
                    ?>
					</span></div>
            </div>
        </nav>
    </div>
    <div class="container profile profile-view" id="profile">
        <div class="row">
            <div class="col-md-12 alert-col relative">
                <div class="alert alert-info absolue center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><span>Profile save with success</span></div>
            </div>
        </div>
        <form method="post">
            <div class="form-row profile-row">
                <div class="col-md-4 relative">
                    <div class="avatar" style="height: 250px;">
                        <div class="avatar-bg center" style="background-image: url(
                           <?php 
                                if (!$other_user) {
                                    $filename="user_images/" . getUserImageName($_SESSION["user_id"]);
                                } else {
                                $filename="user_images/" . getUserImageName($var_profile_user);
                                }
                                if (file_exists($filename)) {
                                    echo "$filename";
                                } else {
                            echo"&quot;assets/img/2.jpg&quot;";
                                }
                           ?> 
                            );"></div>
                        <h1><?php echo $users['first_name'] . " " . $users['last_name'];?></h1>
                    </div>
					<div><br></div>
                    <div class="form-group"><label><b>Email:</b></label><?php echo "\t".$users['email'] ?></div>
					<div class="form-group"><label><b>Gender:</b></label><?php echo "\t".$usersBio['Gender'] ?></div>
                    <div class="form-group"><label><b>Age:</b></label><?php echo "\t" . $usersBio['Age'] ?> </div>

                </div>
                <div class="col-md-8">
                    <h1>Profile</h1>
                    <hr>
					<div>
					</div>
                    <div><p><?php echo  $usersBio['Description'] ?></p></div>

					<div>
					<br>
					</div>
                    <?php if (!$other_user) {
                    echo '<a class="btn btn-primary form-btn"  role="button" href="interests.php">Edit Hobbies</a>';
                    }
                    ?>
                    <div class="form-row">
                        <div class="col-sm-12 col-md-6">
                        </div>
						<div class="col-sm-12 col-md-6">
					   </div>
                    </div>
                    <hr>
                    <div class="form-row">
                    <div class="form-row">
                        <div class="table">
                        <table class="table">
                            <thead class= "thead-dark">
                                <tr>
                                    <th>Hobbies</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                foreach ($interests as &$value) {
                                    echo '<tr><td>' . $value["interest_name"]. '</td></tr>';
                                }
                            ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
					<div class="col-sm-12 col-md-6">
                        </div>
                        <div class="col-sm-12 col-md-6">
						        <div class="form-group"><label><h3>Gender Preferences: &nbsp</h3></label><label id="labelness"></label></div>
                            <div>
                        </div>
                        <?php 
                        if (!$other_user) {
                        echo '<div>
                            <div>
							<form name="formid">
								<select id="GENDERPREF" name="GENDERPREF" onchange="getPref()">
								<option value="None">None</option>
								  <option value="Female">Female</option>
								  <option value="Male">Male</option>
								  <option value="Both" >Both</option>
								</select>
								</form>
                                </div>
                       </div>';
                        } else {
                            echo '<p>' . getUserGenderPreference($var_profile_user) . "</p>";
                        }
                       ?>
					   </div>
                        <?php
                        if (!$other_user) {
                            echo '<div class="col-md-12 content-right"><button class="btn btn-primary form-btn" name="submit" type="submit">SAVE </button><button class="btn btn-danger form-btn" type="reset">CANCEL </button></div>';
                        }
                        ?>
                        </div>
                </div>
            </div>
        </form>
			<?php 
			require("connection.php");
		if(isset($_POST['submit'])){
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
</body>

</html>