<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>GithubCoin add new job</title>
</head>
<body> 
<a href="http://hayoou.com/githubcoin"> GithubCoin offical website</a><br>
<?php
/*	require dirname(dirname(__FILE__)).'/ranklist.php';
	exit();
*/
	$address = $_GET['address'];
	$username = $_GET['username'];

	require dirname(__FILE__).'/db.php';
	if(isset($username[40]) || strlen($address) !=42 ){
		echo "error username or address";
		exit();
	}

	$timenow = time();
	$sql = "select * from user where username='$username' limit 1";
	$re = mysqli_query($link,$sql);
	$row_user = mysqli_fetch_array($re);
	$last_update_time = $row_user['last_update_time'];
	if(!$row_user[0]){
		$sql = "INSERT INTO `user` (`id`, `username`, `address`, `received`, `last_update_time`, `last_calculate_time`, `create_time`, `address2`, `gitee_username`) VALUES (NULL, '$username', '$address', '0.00', '$timenow', '0', '$timenow', '', '');";
		$re = mysqli_query($link,$sql);
		$last_update_time = 0;
		$row_user = array();
		$row_user['address'] =$address;
	}else{

	}

	if($row_user['address']!= $address && $row_user['address']!=""){
		$address = $row_user['address'];
		echo "error address,already have one:".$row_user['address'];
		exit();
	}
	if($row_user['address'] == ""){
		$row_user['address'] =$address;
		$sql = "update user set address='$address' where username='$username' limit 1";
		$re = mysqli_query($link,$sql);
	}

	if($username=="testt"){

	}else if (  $last_update_time + 3600 * 8 > $timenow  ){
		if($row_user['try_cnt'] <=3 && substr($row_user['info'], 0,2)!="OK" && $row_user['info']!="") {
			$sql = "update user set try_cnt = try_cnt + 1 where username='$username' limit 1";
			$re = mysqli_query($link,$sql);
		}else{
			echo "wait ". ($last_update_time + 3600 * 8 - $timenow )/3600 ." hour ,then try again.<br>";
			echo "info :".$row_user['info']."<br>";
			$_GET['MYSQLlink'] = $link;
			require dirname(dirname(__FILE__)).'/ranklist.php';
			exit();
		}	
	}else{
		$sql = "update user set try_cnt = 0 where username='$username' limit 1";
		$re = mysqli_query($link,$sql);
	}

	$sql = "update user set last_update_time=$timenow,info='' where username='$username' limit 1";
	$re = mysqli_query($link,$sql);

	writefile($username,$address);
	echo "Your job is insert , please wait for 2min.<br>";

	function writefile($fn,$txt){
		$file = fopen(dirname(__FILE__)."/fetch_job/$fn","w");
		fwrite($file,$fn."\n".$txt);
		fclose($file);
	}

	$_GET['MYSQLlink'] = $link;
	require dirname(dirname(__FILE__)).'/ranklist.php';