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
<div class="header-blue" style="background-color: rgb(195,12,23);">
    <nav class="navbar navbar-light navbar-expand-md navigation-clean-search">
        <div class="container-fluid"><a class="navbar-brand" href="discover.php">Limerick Lovers</a>
            <button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav">
                    <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#">More Links </a>
                        <div class="dropdown-menu" role="menu"><a class="dropdown-item" role="presentation" href="discover.php">Discover</a><a class="dropdown-item" role="presentation" href="accountInfo.php">Account Info</a><a class="dropdown-item" role="presentation"
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
    <div class="article-list">
        <div class="container">
            <div class="intro">
                <h2 class="text-center">Interests</h2>
                <p class="text-center">Here you have the ability to change/update your interests or add new one's.</p>
            </div>
			<form method="post" action="interests.php">
				<div class="row articles">
					<div class="col-sm-6 col-md-4 item"><img class="img-fluid" src="assets/img/People%20Playing%20Basketball%20On%20Basketball%20Court.jpg">
						<h3 class="name">Remove Your Current Interests</h3>
						<?php
							include "connection.php";
							$sql = "SELECT interest_id FROM Interests WHERE user_id=\"{$_SESSION['user_id']}\"";
							$results = $con->query($sql);
							if($results->num_rows === 0)
							{
								$no_interests = true;
								print "<h4 class=\"text-center\">You currently do not have any interests selected.</h4>";
							} else {
								$interest_ids=array();
								$no_interests = false;
								while($row = $results->fetch_assoc()){
									$interest_ids[]=$row['interest_id'];	
								}
								
								foreach ($interest_ids as $value)
								{
									$sql = "SELECT interest_name FROM `Available Interests` WHERE interest_id=\"{$value}\"";
									$result = $con->query($sql);
									while($row = $result->fetch_assoc()){
										print "<div class=\"form-check\"><input class=\"form-check-input\" type=\"checkbox\" id=\"formCheck-1\" name=\"already-{$value}\" ><label class=\"form-check-label\" for=\"formCheck-1\">{$row['interest_name']}</label></div>";
									}
								}
							}
						?>
					</div>
					<div class="col-sm-6 col-md-4 item"></div>
					<div class="col-sm-6 col-md-4 item"><img class="img-fluid" src="assets/img/Sea%20Surfing%20Adventure%20Sport%20In%20Blue%20Ocean.jpg">
						<h3 class="name">Add Interests</h3>
						<?php
							$not_selected=0;
							$interest_id2=array();
							if($no_interests === true) {
								$sql = "SELECT * FROM `Available Interests`";
									$result = $con->query($sql);
									while($row = $result->fetch_assoc()){
										print "<div class=\"form-check\"><input class=\"form-check-input\" type=\"checkbox\" id=\"formCheck-1\" name=\"not-{$row['interest_id']}\" ><label class=\"form-check-label\" for=\"formCheck-1\">{$row['interest_name']}</label></div>";
										$not_selected++;
									}
							} else {
								$sql = "SELECT * FROM `Available Interests`";
									$result = $con->query($sql);
									while($row = $result->fetch_assoc()){
										if( !in_array($row['interest_id'],$interest_ids)) {
											print "<div class=\"form-check\"><input class=\"form-check-input\" type=\"checkbox\" id=\"formCheck-1\" name=\"not-{$row['interest_id']}\" ><label class=\"form-check-label\" for=\"formCheck-1\">{$row['interest_name']}</label></div>";	
											$interest_id2[]=$row['interest_id'];	
											$not_selected++;
										}
									}
							}
						?>
					</div>
				</div>
				<div class="form-group"><input class="btn btn-primary btn-block" type="submit" value="Submit" name="submit-info"></div>
			</form>
			<?php
				if (isset($_POST['submit-info'])) {
					if($no_interests==false) {
						include "connection.php";
						for ($i=0;$i<count($interest_ids);$i++)
						{
							$val = "already-";
							$val .= (string)$interest_ids[$i];
							if(isset($_POST[$val]))
							{
								$sql = "DELETE FROM Interests WHERE user_id=\"{$_SESSION['user_id']}\" AND interest_id=\"{$interest_ids[$i]}\"";
								$results = $con->query($sql);
							}
						} 
						for ($j=0;$j<count($interest_id2);$j++)
						{
							$val = "not-";
							$val .= (string)$interest_id2[$j];
							if(isset($_POST[$val]))
							{
								$sql = "INSERT INTO `Interests` (`user_id`, `interest_id`) VALUES ('{$_SESSION['user_id']}', '{$interest_id2[$j]}')";
								$results = $con->query($sql);
							}
						}
					} else {
						include "connection.php";
						$sql = "SELECT * FROM `Available Interests`";
						$results = $con->query($sql);
						$not_selected=$results->num_rows;
						for ($j=0;$j<$not_selected;$j++)
						{
							$val = "not-";
							$val .= (string)$j;
							if(isset($_POST[$val]))
							{
								$sql = "INSERT INTO `Interests` (`user_id`, `interest_id`) VALUES ('{$_SESSION['user_id']}', '{$j}')";
								$results = $con->query($sql);
							}
						}
					}
					print "<meta http-equiv=\"refresh\" content=\"0.5\">";
				}
			?>
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
            <p class="copyright">Limerick Lovers © 2020</p>
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