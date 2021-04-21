<?php 

function getContents($url,$timeout=10){
    if(!function_exists('curl_init')){
        die('curl扩展没有开启');
    }
    $ua='Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.9';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

    $response =  curl_exec($ch);
    if($error=curl_error($ch)){
        //die($error);
    }
    curl_close($ch);
    return $response;
}

/**
 * 微博的接口需要post请求
 * @param $url url链接
 * @param $postData post数组
 * @return mixed
 */
function postContents($url,$postData=array(),$header=array(),$timeout=10){
    if(!function_exists('curl_init')){
        die('curl扩展没有开启');
    }
    $ua='Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.9';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_URL, $url);
    $response = curl_exec($curl);
    if($error=curl_error($curl)){
        //die($error);
    }
    curl_close($curl);
    //显示获得的数据
    return $response;
}


/**
 * combineURL
 * 拼接url
 * @param string $baseURL   基于的url
 * @param array  $keysArr   参数列表数组
 * @return string           返回拼接的url
 */
 function combineURL($baseURL,$keysArr){
    $combined = $baseURL."?";
    $valueArr = array();

    foreach($keysArr as $key => $val){
        $valueArr[] = "$key=$val";
    }

    $keyStr = implode("&",$valueArr);
    $combined .= ($keyStr);

    return $combined;
}