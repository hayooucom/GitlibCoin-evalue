<?php

this file is only for test!!!
exit();
/*
if(!$_GET['calc_user_score_yes_4596138']){
	exit();
}*/
error_reporting(E_ALL);
require dirname(__FILE__).'/curl.php';

$username = $_GET['username'];
$username = "jwyang";
$username = "youkpan";
echo "calc user value:".$username."<br>";

function get_user_info($user){
	$url  = "https://api.github.com/users/".$user;
	$user_json = getContents($url);
	return json_decode($user_json);
}

$user_info = get_user_info($username);
//var_dump($user_info);
//exit();
function get_user_objects_loop($user ,$feild,$per_page=100,$limit_page = 20,$start=0){
	$user_obj_all  = array();
	for ($i=$start+1; $i < $limit_page + $start +1; $i++) { 
		$url  = "https://api.github.com/users/$user/$feild?per_page=$per_page&page=$i";
		$user_json = getContents($url);
		$user_obj = json_decode($user_json);
		//echo "get_user_objects_loop:$user,$feild,$per_page,$limit_page,".count($user_obj);
		foreach ($user_obj as $key => $value) {
			array_push($user_obj_all , $value);
		}
		if(count($user_obj)<$per_page){
			break;
		}
	}
	
	return $user_obj_all;
}

function get_public_repos($user){

	$result = get_user_objects_loop($user,"repos",100,1);

	echo "get_public_repos count :".count($result)."<br>";
	return $result;
}

$user_public_repos = get_public_repos($username);

//var_dump($user_public_repos);
//exit();
$is_fork_check =False;

function get_public_repos_score($user_public_repos){
	$score = 0;
	global $is_fork_check ;
	foreach ($user_public_repos as $key => $repo) {
		//var_dump($repo);
		//echo $repo->name."<br>";
		if($repo->name == "I_love_GithubCoin"){
			$is_fork_check =True;
			echo "is_fork_check True";
		}
		//your public repos score = public repos: watch_sum *5 + star_sum *3 + fork_sum *10
		$score += $repo->watchers_count * 5;
		$score += $repo->stargazers_count * 3;
		if(!$repo->fork){
			$score += $repo->forks_count * 10;
		}
	}
	return $score;
}

$user_public_repos_score = get_public_repos_score($user_public_repos);
echo "user_public_repos_score:".$user_public_repos_score."<br>";


if(!$link){
	if($_GET['MYSQLlink']){
		$link = $_GET['MYSQLlink'];
	}else{
		require dirname(__FILE__).'/db.php';
		$_GET['MYSQLlink'] = $link ;
	}
}

/*
if(!$is_fork_check){
	echo "you are not fork <a href='https://github.com/hayooucom/I_love_GithubCoin'>https://github.com/hayooucom/I_love_GithubCoin</a> abord! clear address setting.";

	$sql = "update user set address='' where username='$username' limit 1";
	$re = mysqli_query($link,$sql);

	exit;
}
*/
$user_followers_count = $user_info->followers;
function get_followers($user){
	/*$user_followers_all  = array();
	for ($i=0; $i < 20; $i++) { 
		$url  = "https://api.github.com/users/$user/followers?per_page=100&page=$page";
		$user_json = getContents($url);
		$user_obj = json_decode($user_json);
		foreach ($user_obj as $key => $value) {
			array_push($user_followers_all , $value);
		}
	}*/
	global $user_followers_count;
	$randidx = 0;
	if($user_followers_count > 300){
		$randidx = rand(0, $user_followers_count/100-2 );
	}
	$result = get_user_objects_loop($user,"followers",100,2,(int)($randidx));
	echo "user_followers_all count :".count($result)."<br>";
	return $result;
}


$user_followers = get_followers($username);
echo "user_followers:".count($user_followers)."<br>";

function get_followers_score($user_followers){
	global $user_followers_count;
	$score = 0;
	$count  =0;
	$randidx = 0;
	if(count($user_followers)>12){
		$randidx = rand(0,count($user_followers)-11);
	}

	foreach ($user_followers as $key => $repo) {
		$count ++;
		if($count < $randidx){
			continue;
		}
		if($count >= 10 + $randidx){
			break;
		}
		//if ($key == "url"){
			$url = $repo->url;//$value;
			$user_json = getContents($url);
			$user_obj = json_decode($user_json);
			if($user_obj){
				$score += $user_obj->public_repos;
				$score += $user_obj->followers * 6;				
			}
		//}
	}

	$score = $score * $user_followers_count / 10;

	return $score;
}

$user_followers_score = get_followers_score($user_followers) *0.01;
echo "user_followers_score:".$user_followers_score."<br>";
/*
your public repos score = public repos: watch_sum *5 + star_sum *3 + fork_sum *10

your followers score = his public repos count + followers count * 6

your activity score = your public repos count + followers count * 6 + (your valid commit count) /5

your GTC = 100 * ( (your public repos score) + (your followers score) + (your activity score))
*/

$user_activity_score = $user_info->public_repos + $user_info->followers *6;

echo "user_activity_score:".$user_activity_score."<br>";

$total_score = $user_public_repos_score + $user_followers_score +  $user_activity_score;

echo "total_score:".$total_score."<br>";


if($_GET['update_GTC']){
 	$GTC_new = $row_user['total_score'] ;
 	$received  = $GTC_new - $row_user['received'];
 	if($received >0){
	 	$timee = time();
		$sql = "update user set received=received + $received ,last_update_time=$timee where username='$username' limit 1";
		$re = mysqli_query($link,$sql);

		sendtransfer("",$row_user['address'],$GTC_new);
	}
}
 