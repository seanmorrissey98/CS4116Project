<?php 
session_start();
include 'connection.php';
if ( !isset($_POST['email'], $_POST['password']) ) {
	exit('Please fill both the username and password fields!');
}
if ($stmt = $con->prepare('SELECT user_id, password FROM user WHERE email = ?')) {
	$stmt->bind_param('s', $_POST['email']);
	$stmt->execute();
	$stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $password);
        $stmt->fetch();
            //Need to verify password using whatever hash method we use
            if ($_POST['password'] == $password) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['email'];
            $_SESSION['user_id'] = $user_id;
        } else {
            echo 'Incorrect password!';
        }
    } else {
        echo 'Incorrect username!';
    }
	$stmt->close();
}
?>