<?php

require("connection.php");

function getUsersBio($id){
	include "connection.php";
	$array=array();
	
	
	$sql=("SELECT * FROM `Profile` WHERE user_id=".$id);
		$result = $con->query($sql) or die($con->error);
		while($row = $result->fetch_assoc()){
			$array["id"]=$row["user_id"];
			$array["Age"]=$row["Age"];
			$array["Gender"]=$row["Gender"];
			$array["Seeking"]=$row["Seeking"];
			$array["Description"]=$row["Description"];
			$array["Drinker"]=$row["Drinker"];
			$array["Smoker"]=$row["Smoker"];			
			}
		$con->close();
					
	return $array;
}

function getUser($id){
		include "connection.php";
	$array=array();
	
	
	$sql=("SELECT * FROM `User` WHERE user_id=".$id);
		$result = $con->query($sql) or die($con->error);
		while($row = $result->fetch_assoc()){
			$array["id"]=$row["user_id"];
			$array["first_name"]=$row["first_name"];
			$array["last_name"]=$row["last_name"];
			$array["email"]=$row["email"];
			$array["user_type"]=$row["user_type"];			
			}
		$con->close();
					
	return $array;
}

function getAvailInterests(){
		include "connection.php";
	$array=array();
	$sql=("SELECT * FROM `Available Interests`");
	$num=0;
		$result = $con->query($sql) or die($con->error);
		while($row = $result->fetch_assoc()){
			$temp =array();
			$temp["interest_id"]=$row["interest_id"];
			$temp["interest_name"]=$row["interest_name"];
			$array[$num]=$temp;
			$num++;
			}
		$con->close();
					
	return $array;
}
?>