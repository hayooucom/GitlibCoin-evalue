<?php

if(!$_GET['calc_user_api...']){
	exit();
}
//error_reporting(E_ALL);
require_once dirname(__FILE__).'/curl.php';
require_once dirname(__FILE__)."/functions.php";

$username = $_GET['username'];

echo "calc user value:".$username."<br>";


//$user_info = get_user_info($username);

$query = get_graphQL_query($username);
$user_contribute = get_user_contribute_graphQL($username,$query);
$query2 = get_graphQL_query_last_followers($username);
$user_contribute_last_followers = get_user_contribute_graphQL($username,$query2);

if(!$link){
	if($_GET['MYSQLlink']){
		$link = $_GET['MYSQLlink'];
	}else{
		require_once dirname(__FILE__).'/db.php';
		$_GET['MYSQLlink'] = $link ;
	}
}

if(!$user_contribute && count($user_contribute)==0){

	echo "error no such user in github.<br>";

	$sql = "update user set info='fetch user error or timeout,try again',try_cnt = try_cnt+1 where username='$username' limit 1";
	$re = mysqli_query($link,$sql);

	return;
}

$user_pop_repos = $user_contribute->data->user->repositories->nodes;

$user_followers = $user_contribute->data->user->followers;
$user_followers2 = $user_contribute_last_followers->data->user->followers;

$user_followers_count = $user_followers->totalCount;
$user_is_owner = $user_contribute->data->user->repository;
$user_repositoriesContributedTo = $user_contribute->data->user->repositoriesContributedTo;


//$user_public_repos = get_public_repos($username);

//var_dump($user_public_repos);
//exit();
//$is_fork_check =False;

//$user_public_repos_score = get_public_repos_score($user_public_repos);
$user_public_repos_score = get_public_repos_score_graphQL($username,$user_pop_repos);
echo "user_public_repos_score:".$user_public_repos_score."<br>";

if(!$user_is_owner){
	echo "you are not fork <a href='https://github.com/hayooucom/I_love_GitlibCoin'>https://github.com/hayooucom/I_love_GitlibCoin</a> abord! clear address setting.<br>";

	$sql = "update user set address2=address,info='no ownership,try again',try_cnt = try_cnt+1 where username='$username' limit 1";
	$re = mysqli_query($link,$sql);

	return;
}else{
	$sql = "update user set valid=1,address2=address where username='$username' limit 1";
	$re = mysqli_query($link,$sql);
}


/*
$user_followers_count = $user_info->followers;
$user_followers = get_followers($username);
echo "user_followers:".$user_followers_count."<br>";


$user_followers_score = get_followers_score($user_followers) ;
echo "user_followers_score:".$user_followers_score."<br>";
*/

$user_followers_score_first100 = get_followers_score_graphQL($user_followers);
$user_followers_score_last100 = get_followers_score_graphQL($user_followers2);

$user_followers_score = 1000 * (($user_followers_score_first100 + $user_followers_score_last100 )/2/100) * $user_followers_count ;

echo "user_followers_score:".$user_followers_score." (user_followers_score_first100:$user_followers_score_first100,user_followers_score_last100:$user_followers_score_last100)<br>";
/*
your public repos score = public repos: watch_sum *5 + star_sum *3 + fork_sum *10

your followers score = his public repos count + followers count * 6

your activity score = your public repos count + followers count * 6 + (your valid commit count) /5

your GTC = 100 * ( (your public repos score) + (your followers score) + (your activity score))
*/
/*
$user_activity_score = $user_info->public_repos + $user_info->followers *6;

echo "user_activity_score:".$user_activity_score."<br>";

$total_score = ($user_public_repos_score + $user_followers_score *0.01 +  $user_activity_score)*30;

echo "total_score:".$total_score."<br>";
*/

$user_repositoriesContributedTo_score = get_repositoriesContributedTo_scroe($user_repositoriesContributedTo);

$user_activity_score = $user_followers_count * 6 + $user_repositoriesContributedTo_score ;
echo "user_activity_score:".$user_activity_score."<br>";

$total_score = $user_public_repos_score + $user_followers_score + $user_activity_score;
//-0.96^(0.0002*x)+1
//https://zh.numberempire.com/graphingcalculator.php?functions=-0.96%5E(0.0002*x)%2B1&xmin=0&xmax=500000&ymin=-1&ymax=2&var=x

$GTC_reward = (1 - pow(0.96,0.002 * $total_score))*1000000 ;
echo "GTC_reward:".$GTC_reward."<br>";


if($_GET['update_GTC...']){
 	$GTC_new = $GTC_reward ;
 	$received  = $GTC_new - $row_user['received'];
 	echo "Received this time:".$Received."<br>";

 	if($received >0){
	 	$timee = time();
	 	$datee = date("Y-m-d H:i:s");
		$sql = "update user set received=received + $received ,last_update_time=$timee,info='OK! $datee',try_cnt = 0 where username='$username' limit 1";
		$re = mysqli_query($link,$sql);

		sendtransfer("",$row_user['address'],$received);
	}
}
