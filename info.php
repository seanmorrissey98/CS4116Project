<?php
session_start();
function logout()
{
    $_SESSION = array();
    session_destroy();
    header("Location: index.html");
    exit;
}
?>
<?php
	if (!isset($_COOKIE['email'])) {
		setcookie("enterinfo","Please enter an email and password first.",time()+3600);
		header("Location: signup.php");
		exit;
	}
	function goToNextPage() {
		setcookie("email",0,time()-3600);
		setcookie("password",0,time()-3600);
		setcookie("error_message",0,time()-3600);
		header("Location: interests.php");
		exit;
	}
	if (isset($_POST['submit-info'])) {
		if ( $_POST['firstname'] == "" or $_POST['surname'] == "" or !isset($_POST['age']) or $_POST['description'] == "") {
			setcookie("error_message","One or more fields empty, please try again.",time()+60);
			header("Refresh:0");
		} 
		else {
			if($_FILES["user-image"]["name"] !== "")
			{
				//Uploading a profile picture
				if ( $_POST['firstname'] !== "" or $_POST['surname'] !== "" or $_POST['age'] !== "" or $_POST['description'] !== "") {
					$check = getimagesize($_FILES["user-image"]["tmp_name"]);
					if($check !== false) {
						//NEED TO CHANGE THIS PATH ON EACH INDEPENDENT MACHINE AND HIVE
						$name=basename($_FILES["user-image"]["name"]);
						$folder="user_images/";
						$target_dir=$folder . $name;
						$copy_dir= "user_images/" . $name;
						chmod($_FILES["user-image"]["tmp_name"], 0777); 
						copy($_FILES["user-image"]["tmp_name"], $copy_dir);
						if (move_uploaded_file($_FILES["user-image"]["tmp_name"], $target_dir)) {
							echo "Uploaded";
						} else {
							echo "File was not uploaded";
						}
						//move_uploaded_file($_FILES["user-image"]["tmp_name"], "$folder".$_FILES["user-image"]["name"]);
						include "connection.php";
						$sql = "INSERT INTO `User` (`first_name`, `last_name`, `email`, `password`, `user_type`) VALUES ('{$_POST['firstname']}', '{$_POST['surname']}', '{$_COOKIE['email']}', '{$_COOKIE['password']}', 'user')";
						$result = $con->query($sql);
						$smoker=0;
						$drinker=trim($_POST['drinker']);
						if( trim($_POST['smoker']) == "Yes") {
							$smoker = 1;
						} else {
							$smoker = 0;
						}
						$sql = "SELECT user_id FROM User WHERE email=\"{$_COOKIE['email']}\"";
						$result = $con->query($sql);
						session_start();
						while($row = $result->fetch_assoc()){
							$sql = "INSERT INTO `Profile` (`user_id`, `Age`, `Gender`, `Seeking`, `Photo`, `Banned`, `Description`, `Drinker`, `Smoker`, `Verified`) VALUES ('{$row['user_id']}', '{$_POST['age']}', '{$_POST['gender']}', '{$_POST['seeking']}', '{$name}', '0', '{$_POST['description']}', '{$drinker}', '{$smoker}', '0')";
							$result_two = $con->query($sql);
							setcookie("user_id",$row['user_id'],time()+3600);
							$_SESSION["user_id"] = $row['user_id'];
						}
						$con->close();
                        $_SESSION["first_name"] = $_POST['firstname'];
                        $_SESSION["last_name"] = $_POST['surname'];
                        $_SESSION["email"] = $_COOKIE['email'];
						$_SESSION["seeking_gender"] = $_POST['seeking'];
						$_SESSION["drinker"]=$drinker;
						$_SESSION["smoker"]=$smoker;
						$_SESSION["loggedIn"]=true;
						goToNextPage();
					} else {
						setcookie("error_message","File is not an image or is too big.",time()+60);
					}
				}
			} else {
				//not uploading a profile picture
				if ( $_POST['firstname'] !== "" or $_POST['surname'] !== "" or $_POST['age'] !== "" or $_POST['description'] !== "") {
					include "connection.php";
					$sql = "INSERT INTO `User` (`first_name`, `last_name`, `email`, `password`, `user_type`) VALUES ('{$_POST['firstname']}', '{$_POST['surname']}', '{$_COOKIE['email']}', '{$_COOKIE['password']}', 'user')";
					$result = $con->query($sql);
					$drinker=trim($_POST['drinker']);
					if( trim($_POST['smoker']) == "yes") {
						$smoker = 1;
					} else {
						$smoker = 0;
					}
					$sql = "SELECT user_id FROM User WHERE email=\"{$_COOKIE['email']}\"";
					$result = $con->query($sql);
					session_start();
					while($row = $result->fetch_assoc()){
						$sql = "INSERT INTO `Profile` (`user_id`, `Age`, `Gender`, `Seeking`, `Photo`, `Banned`, `Description`, `Drinker`, `Smoker`, `Verified`) VALUES ('{$row['user_id']}', '{$_POST['age']}', '{$_POST['gender']}', '{$_POST['seeking']}', 'no_picture.jpg' ,'0', '{$_POST['description']}', '{$drinker}', '{$smoker}', '0')";
						$result_two = $con->query($sql);
						setcookie("user_id",$row['user_id'],time()+3600);
						$_SESSION["user_id"] = $row['user_id'];
					}
					$con->close();
                    $_SESSION["first_name"] = $_POST['firstname'];
                    $_SESSION["last_name"] = $_POST['surname'];
                    $_SESSION["email"] = $_COOKIE['email'];
					$_SESSION["seeking_gender"] = $_POST['seeking'];
					$_SESSION["drinker"]=$drinker;
					$_SESSION["smoker"]=$smoker;
					$_SESSION["loggedIn"]=true;
					goToNextPage();
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
    <link rel="stylesheet" href="assets/css/Article-List.css">
    <link rel="stylesheet" href="assets/css/Footer-Basic.css">
    <link rel="stylesheet" href="assets/css/Header-Blue.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/css/bootstrap-slider.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">
    <link rel="stylesheet" href="assets/css/Lightbox-Gallery.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Dark.css">
    <link rel="stylesheet" href="assets/css/Profile-Card.css">
    <link rel="stylesheet" href="assets/css/Profile-Edit-Form-1.css">
    <link rel="stylesheet" href="assets/css/Profile-Edit-Form.css">
    <link rel="stylesheet" href="assets/css/Registration-Form-with-Photo.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div class="header-blue" style="background-color: rgb(195,12,23);">
        <nav class="navbar navbar-light navbar-expand-md navigation-clean-search">
            <div class="container-fluid"><a class="navbar-brand" href="index.html">Limerick Lovers</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse"
                    id="navcol-1">
                    <ul class="nav navbar-nav">
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
    <div class="login-dark" style="background-image: url(&quot;assets/img/couple.jpg&quot;);">
        <form method="post" action="info.php" enctype="multipart/form-data">
            <h2 class="sr-only">Login Form</h2>
            <div class="illustration"><i class="icon ion-ios-person"></i></div>
            <div class="form-group"><input class="form-control" type="text" placeholder="First Name" name="firstname"><input class="form-control" type="text" placeholder="Surname" name="surname"><input class="form-control" type="number" placeholder="Age" name="age" min="0">
				<textarea class="form-control" name="description" placeholder="Description"></textarea>
				<br>
				<label class="form-check-label" for="gender-list"> Gender: </label>
				<select name="gender" id="gender-list" class="custom-select">
					<option value="male">Male</option>
					<option value="female">Female</option>
					<option value="other">Other</option>
				</select>
				<br>
				<label class="form-check-label" for="seeking-list">Seeking: </label>
				<select name="seeking" id="seeking-list" class="custom-select">
					<option value="male">Male</option>
					<option value="female">Female</option>
					<option value="other">Other</option>
				</select>
				<br>
				<label class="form-check-label" for="drinker-list">Drinker: </label>
				<select name="drinker" id="drinker-list" class="custom-select">
					<option value="constantly">Constantly</option>
					<option value="Most days">Most days</option>
					<option value="Social Drinker">Social Drinker</option>
					<option value="no">No</option>
				</select>
				<br>
				<label class="form-check-label" for="smoker-list">Smoker: </label>
				<select name="smoker" id="smoker-list" class="custom-select">
					<option value="yes">Yes</option>
					<option value="no">No</option>
				</select>
				<label class="form-check-label" for="image-input">Upload an image: </label>
				<input id="image-input" class="form-control" type="file" placeholder="Image" name="user-image">
            </div>
			<?php
				if(isset($_COOKIE['error_message'])) {
					print "<h4 class=\"text-center\">{$_COOKIE['error_message']}</h4>";
				}
			?>
            <div class="form-group"><input class="btn btn-primary btn-block" type="submit" value="Submit" name="submit-info"></div><a class="forgot" href="#">Forgot your email or password?</a></form>
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
            <p class="copyright">Limerick Lovers © 2020</p>
        </footer>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/bootstrap-slider.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
    <script src="assets/js/Profile-Edit-Form.js"></script>
    <script src="assets/js/Range-selector---slider.js"></script>
</body>

</html>