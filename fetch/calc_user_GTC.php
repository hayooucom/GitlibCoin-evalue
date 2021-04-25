<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Caculate how many GithubCoin I can get . </title>
</head>
<body> 

<?php
//error_reporting(E_ALL);

	$address = $_GET['address'];
	$username = $_GET['username'];

	require dirname(__FILE__).'/db.php';
	echo "calc user value:".$username."<br>";
	if(!$username || isset($username[40]) || strlen($address) !=42 ){
		echo "error username or address";
		exit();
	}
	if(strpos($username,"'")!==False || strpos($username,"\"")!==False){
		echo "you are Dangerous person";
		exit();
	}
	
	$username=mysqli_real_escape_string($link,$username);
	$address=mysqli_real_escape_string($link,$address);
	
	$timenow = time();
	$sql = "select * from user where username='$username' limit 1";
	$re = mysqli_query($link,$sql);
	$row_user = mysqli_fetch_array($re);
	$last_calculate_time = $row_user['last_calculate_time'];
	if(!$row_user[0]){
		$sql = "INSERT INTO `user` (`id`, `username`, `address`, `received`, `last_update_time`, `last_calculate_time`, `create_time`, `address2`, `gitee_username`) VALUES (NULL, '$username', '$address', '0.00', '0', '$timenow', '$timenow', '', '');";
		$re = mysqli_query($link,$sql);
		$last_calculate_time = 0;
		$row_user = array();
		$row_user['address'] =$address;
	}else{
		$sql = "update user set last_calculate_time=$timenow where username='$username' limit 1";
		$re = mysqli_query($link,$sql);
	}

	if($row_user['address']!= $address && $row_user['address']!=""){
		$address = $row_user['address'];
		echo "error address,already have one:".$row_user['address'];
	}

	if($last_calculate_time + 3600 * 1 > $timenow ){
		echo "wait ". ($last_calculate_time + 3600 * 1 - $timenow )/60 ." min ,then try again.";
		exit();
	}


	$_GET['calc_user_api...'] = 1;
	$_GET['MYSQLlink'] = $link;

	require dirname(__FILE__)."/get_user_score.php";

	echo "your total GTC reward:".($GTC_reward)."<br>";

	echo "your GTC will get this time:".($GTC_reward - $row_user['received']);

	
	