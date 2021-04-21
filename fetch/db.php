<?php
		//header("Access-Control-Allow-Origin:*");
		//header("Content-Type: text/html; charset=UTF-8");

		$link=mysqli_connect('127.0.0.1','account','your pass word');

		if($link)
		{
			mysqli_select_db( $link,'githubcoin');

			mysqli_query($link,"SET NAMES 'utf8'"); 
			mysqli_query($link,"SET CHARACTER_SET_CLIENT=utf8"); 
			mysqli_query($link,"SET CHARACTER_SET_RESULTS=utf8"); 
    }
?>