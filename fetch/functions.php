<?php

function get_user_info($user){
	$url  = "https://api.github.com/users/".$user;
	$user_json = getContents($url);
	//var_dump($user_json);
	return json_decode($user_json);
}


//var_dump($user_info);
//exit();
function get_user_objects_loop($user ,$feild,$per_page=100,$limit_page = 20,$start=0){
	$user_obj_all  = array();
	for ($i=$start+1; $i < $limit_page + $start +1; $i++) { 
		$url  = "https://api.github.com/users/$user/$feild?per_page=$per_page&page=$i";
		if($_GET['dbg'])
			echo "getting $user/$feild $i  $url<br>";
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
	if($i == $start +1 && strlen($user_json)<500){
		echo $user_json;
	}
	if($_GET['dbg'] && count($user_obj_all)==0)
		echo $user_json;
	
	
	return $user_obj_all;
}
function get_public_repos($user){

	$result = get_user_objects_loop($user,"repos",100,5,0);

	echo "get_public_repos count :".count($result)."<br>";
	return $result;
}


function get_public_repos_score($user_public_repos){
	$score = 0;
	global $is_fork_check ;
	foreach ($user_public_repos as $key => $repo) {
		//var_dump($repo);
		//echo $repo->name."<br>";
		if($repo->name == "I_love_GithubCoin" || $repo->name == "githubcoin"){
			$is_fork_check =True;
			echo "is_fork_check True<br>";
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
	echo "user_followers_test_get count :".count($result)."<br>";
	return $result;
}


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


function sendtransfer($from ,$to,$amount){
	$amount_all = $amount;
 	$start = 100000;

 	for ($j=0; $j < 5 ; $j++) { 
		for ($i=0; $i < (int) ($amount_all/$start); $i++) { 
			
			sendtransfer2($from ,$to,$start);
			
			echo "trans $start,left:$amount_all <br>";
		}
		$amount_all = $amount_all % $start;
		$start = (int) ($start /10);
 	}
	sendtransfer2($from ,$to,$amount_all);
}
function sendtransfer2($from ,$to,$amount){
	$hex = "0x" . dechex(1000000000000000000*$amount);
	switch ($amount) {
		case 100000:
			$hex = "0x152D02C7E14AF6800000";
			break;

		case 10000:
			$hex = "0x21E19E0C9BAB2400000";
			break;
		
		case 1000:
			$hex = "0x3635C9ADC5DEA00000";
			break;
		case 1000:
			$hex = "0x3635C9ADC5DEA00000";
			break;
		case 100:
			$hex = "0x56bc75e2d63100000";
			break;
		case 10:
			$hex = "0x8AC7230489E80000";
			break;
		
	}
	//--allow-insecure-unlock
	//1100000000000000
	//1000000000000000
	//0x3e871b540c000
	//0x56bc75e2d63100000
	//6250000000000000000
	$txdata = <<< HTMLL
{"from":"0xdae896c3a7730db51fa81010f9157b4ab179f0a8",  "to": "$to",  "value":"$hex"  }
HTMLL;
	echo $txdata;
   // echo $txdata;  gas: "30400", gasPrice: "0x9184e72a000", 
   return post($txdata);
}

function post($post_data)
{
	$url = "http://API.hayoou.com/input your own api";
 
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);//设置超时时间

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// post数据
	curl_setopt($ch, CURLOPT_POST, 1);
	// post的变量
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(  
            'Content-Type: application/json; charset=utf-8',  
            'Content-Length: ' . strlen($post_data),
            'Referer:http://hayoou.com/ethwallet/')  
        );  

	$output = curl_exec($ch);
	curl_close($ch);
	//echo "1";
	//print_r($output);
	return $output;
}


function get_repositoriesContributedTo_scroe($user_repositoriesContributedTo){
  $score1 = 0;
  $nodes= $user_repositoriesContributedTo->nodes;
  foreach ($nodes as $key => $repo) {
      $score2 = 0;
      $score2 += $repo->watchers->totalCount*5;
      $score2 += $repo->stargazerCount*3;
      $score2 += $repo->forkCount*10;

      //-0.993^(0.02*x)+1
      //https://zh.numberempire.com/graphingcalculator.php?functions=-0.993%5E(0.02*x)%2B1&xmin=0&xmax=50000&ymin=-1&ymax=2&var=x
      $score_norm = 1 - pow(0.993 ,0.02 * $score2); // [0~1]\

      $score1 += $score_norm;
  }

  return $score1 * 100 + $user_repositoriesContributedTo->totalCount *10;
}


function get_followers_score_graphQL($user_followers){
 
  $score = 0;
  $count  =0;
  $randidx = 0;
  //print_r($user_followers);
  $repo_nodes = $user_followers->nodes;
  foreach ($repo_nodes as $key => $useinfo) {

    $score1 = 0;

    $user_followers_cnt = $useinfo->followers->totalCount;
    $user_nodes = $useinfo ->repositories->nodes;
    foreach ($user_nodes as $key1 => $repo) {
      $score2 = 0;
      $score2 += $repo->watchers->totalCount*5;
      $score2 += $repo->stargazerCount*3;
      $score2 += $repo->forkCount*10;

      //-0.993^(0.02*x)+1
      //https://zh.numberempire.com/graphingcalculator.php?functions=-0.993%5E(0.02*x)%2B1&xmin=0&xmax=50000&ymin=-1&ymax=2&var=x
      $score_norm = 1 - pow(0.993 ,0.02 * $score2); // [0~1]\

      $score1 += $score_norm;
    }
    //echo "$useinfo->name,score1:$score1,$user_followers_cnt<br>";
    //$user_obj->nodes->repositories->totalCount *
    $score += $score1  ;
  }

  return $score;

}

function get_public_repos_score_graphQL($username,$user_pop_repos){
  $score = 0;
  global $is_fork_check;
  foreach ($user_pop_repos as $key => $repo) {
    //var_dump($repo);
    
    if($repo->name == "I_love_GitlibCoin" ||$repo->name == "I_love_GithubCoin" ||
      $repo->name == "I_love_GitcatCoin" ||
      $repo->name == "I_love_GithatCoin" || $repo->name == "GitlibCoin"){
      $is_fork_check =True;
      echo "is_owner_check True<br>";
    }

    //your public repos score = public repos: watch_sum *5 + star_sum *3 + fork_sum *10
    $score += $repo->watchers->totalCount * 5;
    $score += $repo->stargazerCount * 3;
    if(!$repo->isFork){
      $score += $repo->forkCount * 10;
      $score += 10;
      echo "<a href='https://github.com/$username/{$repo->name}'>$repo->name</a>";
      echo " watchers:{$repo->watchers->totalCount} , stars:{$repo->stargazerCount} ,fork:{$repo->forkCount} ";
      echo "<br>";
    }else{
      //echo " -- is_fork from others <br>";
    }
  }
  return $score;
}
function get_user_contribute_graphQL($username,$query){

  $headers = array("Content-Type: application/json",
    "Authorization: bearer your own privet key",
    "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.9");
  $query =json_encode( array('query' => $query ) );
  //echo $query;
  $out = postContents("https://api.github.com/graphql",$query,$headers,60);

  return json_decode($out);
}

function get_graphQL_query($username){
$query = <<<QUERYY
query {
  user(login: "$username") {

    repositories(first: 100,orderBy: {field: STARGAZERS, direction: DESC}) {

      nodes {
        name
        watchers {
          totalCount
        }
        stargazerCount
        forkCount
        isFork
      }
    }

    followers(first:100){
      totalCount
      nodes{
        name
        followers{
          totalCount
        }
        repositories(isFork:false,first:100,orderBy:{field: STARGAZERS, direction: DESC}){
          totalCount
          nodes{
            stargazerCount
            forkCount
            watchers{
              totalCount
            }
          }
        }
      }
    }
    repository(name:"I_love_GithubCoin"){
      id
    }
    repositoriesContributedTo(contributionTypes: [COMMIT, PULL_REQUEST, REPOSITORY], last: 100) {
      totalCount
      nodes {
        nameWithOwner
        watchers{
          totalCount
        }
        stargazerCount
        forkCount
      }
      pageInfo {
        endCursor
        hasNextPage
      }
    }
  }
}
QUERYY;

return $query;
}
function get_graphQL_query_last_followers($username){
$query = <<<QUERYY
query {
  user(login: "$username") {

    followers(last:100){
      totalCount
      nodes{
        name
        followers{
          totalCount
        }
        repositories(isFork:false,first:100,orderBy:{field: STARGAZERS, direction: DESC}){
          totalCount
          nodes{
            stargazerCount
            forkCount
            watchers{
              totalCount
            }
          }
        }
      }
    }
    
  }
}
QUERYY;

return $query;
}

/*
starredRepositories(ownedByViewer:true){
  totalCount
}
topRepositories(orderBy:{field: STARGAZERS, direction: DESC} ){
  totalCount
  
}*/
function get_graphQL_query1($username){
$query = <<<QUERYY
query {
  user(login: "$username") {

    repositories(isFork: true, first: 100,orderBy: {field: STARGAZERS, direction: DESC}) {
      nodes {
        name
        watchers {
          totalCount
        }
        stargazers {
          totalCount
        }
        forks {
          totalCount
        }
      }
    }
     
  }
}
QUERYY;

return $query;
}

//jdah
function get_graphQL_query2($username){
$query = <<<QUERYY
{
  user(login: "$username") {
    followers(first:100){
      totalCount
      nodes{
        name
        repositories(isFork:false,first:100){
          totalCount
        }
      }
    }
    repository(name:"I_love_GithubCoin"){
      id
    }
  }
    
}

QUERYY;

return $query;
}
