<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>GithubCoin do job</title>
</head>
<body> 

<?php
    require dirname(__FILE__).'/db.php';
	$list = scandir(dirname(__FILE__)."/fetch_job/");
	$_GET['calc_user_score_yes_4596138'] = 1;
	$_GET['MYSQLlink'] = $link;
	$count = 0;
	foreach ($list as $value) {
	    
		$filename = dirname(__FILE__)."/fetch_job/".$value;
	    $data = file_get_contents($filename);
	    if(!$data){
	    	continue;
	    }
	    $count ++ ;
	    if ($count >4){
	    	break;
	    }
	    echo $value." data: $data\n<br>";

	    $username = explode("\n",$data)[0];
	    $address = explode("\n",$data)[1];

	    $sql = "select * from user where username='$username' limit 1";
		$re = mysqli_query($link,$sql);
		$row_user = mysqli_fetch_array($re);
		//var_dump($row_user);

		$_GET['GET_GTC...'] = 1;
		$_GET['username'] = $row_user['username'];
		$_GET['address'] = $row_user['address'];
		unlink($filename);
	    include dirname(__FILE__)."/get_user_score.php";
		
	}
