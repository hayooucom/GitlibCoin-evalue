<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>测试你的Github账号价值，免费获取 1万个 Github 币 . get 10,000 Github coin for free</title>
	<link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/1.1.1/weui.min.css"/>
	<style>
	h2 {
		font-weight: 500;
	}
	hr {
		margin-top: 20px;
		margin-bottom: 20px;
		border: 0;
		border-top: 1px solid #eee;
	}
	.img { text-align: center;}
	.copyright{color:#666;text-align: center;margin-top: 30px;}
	a {color:#3c75ca;text-decoration:none;word-wrap: break-word;word-break: normal;}
	a:hover {text-decoration: underline;color: #3c75ca;}


</style>
</head>
<body>
<div style="text-align:center;width:100%">
	 <br>
 <br>
	<h3>Test your Github account value. <br>Get <span style="color:red">10K</span> GithubCoin for free</h3>
	 <br>
	 need check your github account ownership：
 <br>
 Fork <a href='https://github.com/hayooucom/I_love_GithubCoin'>I_love_GithubCoin</a> in github first ! <br> <br>
<div class="weui-form__control-area">
    <div class="weui-cells__group weui-cells__group_form">
		<div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd" style="text-align:center;width:40%" ><label class="weui-label" style="width:100%" >GTC Address:</label></div>
                <div class="weui-cell__bd">
                    <input id="address" class="weui-input" type="text"  placeholder="0x... long press address bar" value="">
                </div>

            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd" style="text-align:center;width:40%" ><label class="weui-label" style="width:100%" >
                username:</label></div>
                <div class="weui-cell__bd">
                    <input id="username" class="weui-input" type="text"   placeholder="your github username" value="">
                </div>
               </div>
		</div>
    </div>
</div>
<br>
Not having GTC wallet? get one : 
<a onclick="get_wallet()" class="weui-btn weui-btn_default"  style="width:220px" target="_blank">GTC Android wallet</a>
 <br>
 <br>

 <a onclick="submit()" class="weui-btn weui-btn_default"  style="width:220px;background: #60d417;color: white;" target="_blank">Submit 提交</a>
<br><br>
Caculate how much i can received<br>
 <a onclick="caculate()" class="weui-btn weui-btn_default"  style="width:120px" target="_blank"> start </a>
 <br>
 <a href='https://github.com/hayooucom/I_love_GithubCoin'> Detail</a>
<!--
 Login with Github:<br>
<div class="img">
	<a href="" onclick='toGithubLogin()' ><img src="./oauth/test/images/github.png" ></a>
</div>
<br>
<div class="img">
	<a href="" onclick='toGithubLogin2()' ><img src="./oauth/test/images/github.png" ></a>
</div>
<br>
 Login with Gitee:<br>
<div class="img">
	<a href="" onclick='toGiteeLogin()' ><img src="./oauth/test/images/gitee.png" ></a>
</div>
-->
<br />
<hr />
</div>
<div>
<?php 
$_GET['link'] = $link;
require  (dirname(__FILE__)).'/ranklist.php';
?>
</div>
<div class="copyright">
	
	<br />
	<a href="http://f.hayoou.com/blogs/entry/GithubCoin-published-for-ervery-github-users-thanks-they-ideas-and-code" target="_blank">GithubCoin</a>
	<br />
	<a href="https://github.com/hayooucom/githubcoin" target="_blank">PC miner on github</a>
	<br />
	<p>Copyright &copy; <a href="http://f.hayoou.com" target="_blank">hayoou.com</a></p>
</div>

</body>
</html>
<script>
function getobj(id){
	return document.getElementById(id);
}

function submit(){
	Address = getobj("address").value
	username = getobj("username").value
 	 if(Address.substr(0,2)=="0x" && Address.length==42 && username.length>4 ){
 	 	window.open("./fetch/add_fetch_job.php?address="+Address+"&username="+username);
 	 }else{
 	 	alert("Address Seems error ,in GithubCoin Wallet APP long press the address tab to copy the address")
 	 }
}

function caculate(){
	Address = getobj("address").value
	username = getobj("username").value
 	 if(Address.substr(0,2)=="0x" && Address.length==42 && username.length>4 ){
 	 	window.open("./fetch/calc_user_GTC.php?address="+Address+"&username="+username);
 	 }else{
 	 	alert("Address Seems error ,in GithubCoin Wallet APP long press the address tab to copy the address")
 	 }
}

 function toGiteeLogin(){
 	 Address = getobj("address").value
 	 if(Address.substr(0,2)=="0x" && Address.length==42){
 	 	var Github=window.open("./gitee_oauth.php?address="+Address);
 	 }else{
 	 	alert("Address Seems error ,in GithubCoin Wallet APP long press the address tab to copy the address")
 	 }
 }


 function toGithubLogin2(){
 	 Address = getobj("address").value
 	 if(Address.substr(0,2)=="0x" && Address.length==42){
 	  
 	 	var Github=window.open("./github_oauth.php?logintype=2&address="+Address);
 	 }else{
 	 	alert("Address Seems error ,in GithubCoin Wallet APP long press the address tab to copy the address")
 	 }
     
 }


 function toGithubLogin(){
 	 Address = getobj("address").value
 	 if(Address.substr(0,2)=="0x" && Address.length==42){
 	 	var Github=window.open("./github_oauth.php?address="+Address);
 	 }else{
 	 	alert("Address Seems error ,in GithubCoin Wallet APP long press the address tab to copy the address")
 	 }
     
 }


function get_wallet() {
	window.open("http://f.hayoou.com/blogs/entry/GithubCoin-published-for-ervery-github-users-thanks-they-ideas-and-code","_blank")
}
</script>