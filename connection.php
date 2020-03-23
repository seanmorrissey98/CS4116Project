<?php
$DATABASE_HOST='hive.csis.ul.ie:3306';
$DATABASE_USER='group06';
$DATABASE_PASS='mfAtT$a?hVB9Uw>j';
$DATABASE_NAME='dbgroup06';
$con=mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if(mysqli_connect_errno() ) {
    exit('Failed to connect to db: ' . mysqli_connect_error());
}
?> 