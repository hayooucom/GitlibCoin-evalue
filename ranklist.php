<style>
table.reference, table.tecspec {
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 4px;
    margin-top: 4px;
}
table.reference tr:nth-child(odd) {
    background-color: #f6f4f0;
}
</style>

<?php
	global $link;
	//error_reporting(E_ALL);
	if(!$link){
		if($_GET['MYSQLlink']){
			$link = $_GET['MYSQLlink'];
		}else{
			require dirname(__FILE__).'/fetch/db.php';
		}
	}

	if($_GET['username'])
		$username = $_GET['username'];

	$sql = "select * from user where username='$username' limit 1";
	$re = mysqli_query($link,$sql);
	$row_user = mysqli_fetch_array($re);
	$users = array();

	array_push(	$users, $row_user);

	$sql = "select * from user order by received desc,id desc limit 50";
	$re = mysqli_query($link,$sql);
	echo "Received GTC rank:<br><table class=\"reference\" style='width:100%' border=1><tbody><tr><td style='width:30%'>Username</td><td style='width:30%'>Total received</td><td style='width:30%'>Info</td></tr>";

	while($row_user2 = mysqli_fetch_array($re)){
		array_push(	$users, $row_user2);
	}
	foreach ($users as $key => $row_user2) {
		$html .= "<tr><td>";
		$username1 = $row_user2['username'];
		$html .= "<a href='https://github.com/$username1' target='_blank'>$username1</a>";
		$html .= "</td><td>";
		$html .=$row_user2['received'];
		$html .= "</td><td>";
		$html .=$row_user2['info'];
		if($row_user2['info'] == "no ownership,try again"){
			$html .= ",please fork:<br><a href='https://github.com/hayooucom/I_love_GithubCoin' target='_blank'>I_love_GithubCoin</a>";
		}
		$html .= "</td></tr>";
	}

	echo $html;
	echo "</tbody ></table >";
	
