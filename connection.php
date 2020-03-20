<?php
$DATABASE_HOST='localhost:3306';
$DATABASE_USER='root';
$DATABASE_PASS='';
$DATABASE_NAME='dbgroup06';
$con=mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if(mysqli_connect_errno() ) {
    exit('Failed to connect to db: ' . mysqli_connect_error());
}
?>