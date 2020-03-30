<?php
	if (isset($_COOKIE['email'])) {
		header("Location: /info.php");
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Article-List.css">
    <link rel="stylesheet" href="assets/css/Footer-Basic.css">
    <link rel="stylesheet" href="assets/css/Header-Blue.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">
    <link rel="stylesheet" href="assets/css/Lightbox-Gallery.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Dark.css">
    <link rel="stylesheet" href="assets/css/Registration-Form-with-Photo.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div>
        <div class="header-blue">
            <nav class="navbar navbar-light navbar-expand-md navigation-clean-search">
                <div class="container-fluid"><a class="navbar-brand" href="/index.html">Limerick Lovers</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse"
                        id="navcol-1">
                        <ul class="nav navbar-nav">
                           
                        </ul>
                        <form class="form-inline mr-auto" target="_self">
                        </form><span class="navbar-text"> <a class="login" href="login.php">Log In</a></span></div>
                </div>
            </nav>
        </div>
    </div>
    <div class="register-photo">
        <div class="form-container">
            <div class="image-holder" style="background-image: url(&quot;assets/img/Photo%20of%20a%20Man%20Carrying%20His%20Partner.jpg&quot;);"></div>
            <form action="signup.php" method="post">
                <h2 class="text-center"><strong>Create</strong> an account.</h2>
                <div class="form-group"><input class="form-control" type="email" name="email" placeholder="Email"></div>
                <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Password"></div>
                <div class="form-group"><input class="form-control" type="password" name="password-repeat" placeholder="Password (repeat)"></div>
				<?php
					if (isset($_COOKIE['enterinfo'])) {
						print "<h4 class=\"text-center\">{$_COOKIE['enterinfo']}</h4>";
						setcookie("enterinfo",0,time()-100);
					}
					if (isset($_POST['submitted'])) {
						if ( $_POST['email'] == "" or $_POST['password'] == "" or $_POST['password-repeat'] == "" ) {
							print "<h4 class=\"text-center\">One or more fields empty, please try again.</h4>";
						}
						if (!isset($_POST['agreed'])) {
							print "<h4 class=\"text-center\">Please check the checkbox.</h4>";
						}
						if ( $_POST['email'] !== "" and isset($_POST['agreed']) and $_POST['password'] == $_POST['password-repeat']) {
							//Need to create a cookie for password and email so we can get it from the next page
							setcookie("email",$_POST['email'],time()+3600);
							setcookie("password",$_POST['password'],time()+3600);
							header("Location: /info.php");
							exit;
						}
					}
				?>
                <div class="form-group">
                    <div class="form-check"><label class="form-check-label"><input class="form-check-input" type="checkbox" name="agreed">I agree to the license terms.</label></div>
                </div>
                <div class="form-group"><input class="btn btn-primary btn-block" type="submit" value="Sign Up" name="submitted"></div><a class="already" href="login.php">You already have an account? Login here.</a></form>
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
    <script src="assets/js/bs-animation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
</body>

</html>