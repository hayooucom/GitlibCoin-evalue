<?php

if(!$_GET['calc_user_score_yes_4596138']){
	exit();
}
//error_reporting(E_ALL);
require_once dirname(__FILE__).'/curl.php';
require_once dirname(__FILE__)."/functions.php";

$username = $_GET['username'];

echo "calc user value:".$username."<br>";


$user_info = get_user_info($username);

if(!$link){
	if($_GET['MYSQLlink']){
		$link = $_GET['MYSQLlink'];
	}else{
		require_once dirname(__FILE__).'/db.php';
		$_GET['MYSQLlink'] = $link ;
	}
}

if(!$user_info && count($user_info)==0){
	var_dump($user_info);
	echo "error no such user in github.<br>";

	$sql = "update user set info='no such user,try again',try_cnt = try_cnt+1 where username='$username' limit 1";
	$re = mysqli_query($link,$sql);

	return;
}


$user_public_repos = get_public_repos($username);

//var_dump($user_public_repos);
//exit();
$is_fork_check =False;

$user_public_repos_score = get_public_repos_score($user_public_repos);
echo "user_public_repos_score:".$user_public_repos_score."<br>";


if(!$is_fork_check){
	echo "you are not fork <a href='https://github.com/hayooucom/I_love_GithubCoin'>https://github.com/hayooucom/I_love_GithubCoin</a> abord! clear address setting.<br>";

	$sql = "update user set address2=address,info='no ownership,try again',try_cnt = try_cnt+1 where username='$username' limit 1";
	$re = mysqli_query($link,$sql);

	return;
}
$user_followers_count = $user_info->followers;


$user_followers = get_followers($username);
echo "user_followers:".$user_followers_count."<br>";


$user_followers_score = get_followers_score($user_followers) ;
echo "user_followers_score:".$user_followers_score."<br>";
/*
your public repos score = public repos: watch_sum *5 + star_sum *3 + fork_sum *10

your followers score = his public repos count + followers count * 6

your activity score = your public repos count + followers count * 6 + (your valid commit count) /5

your GTC = 100 * ( (your public repos score) + (your followers score) + (your activity score))
*/

$user_activity_score = $user_info->public_repos + $user_info->followers *6;

echo "user_activity_score:".$user_activity_score."<br>";

$total_score = ($user_public_repos_score + $user_followers_score *0.01 +  $user_activity_score)*10;

echo "total_score:".$total_score."<br>";


if($_GET['GET_GTC...']){
 	$GTC_new = $total_score ;
 	$received  = $GTC_new - $row_user['received'];
 	if($received >0){
	 	$timee = time();
	 	$datee = date("Y-m-d H:i:s");
		$sql = "update user set received=received + $received ,last_update_time=$timee,info='OK! $datee',try_cnt = 0 where username='$username' limit 1";
		$re = mysqli_query($link,$sql);

		sendtransfer("",$row_user['address'],$GTC_new);
	}
}
