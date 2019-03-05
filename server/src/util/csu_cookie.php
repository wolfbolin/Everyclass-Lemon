<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/3
 * Time: 0:28
 * @param $url1
 * @param $url2
 * @return array|string
 */
//get csu jw cookie
function get_cookie($url1, $url2) {
    $http_request = curl_init();
    $header = [
        "Content-type: application/x-www-form-urlencoded",
        "Host: csujwc.its.csu.edu.cn",
        "Connection: keep-alive",
    ];
    $user_agent = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.146 Safari/537.36";
    curl_setopt($http_request, CURLOPT_URL, $url1);
    curl_setopt($http_request, CURLOPT_HTTPHEADER, $header);  // 添加 header 信息
    curl_setopt($http_request, CURLOPT_HEADER, true);  // 保留请求头
    curl_setopt($http_request, CURLOPT_NOBODY, true);  // 不保留响应内容
    curl_setopt($http_request, CURLOPT_USERAGENT, $user_agent);  // 定义ua
    curl_setopt($http_request, CURLOPT_RETURNTRANSFER, 1);  // 不输出到std
    curl_setopt($http_request, CURLOPT_POST, false);  //使用get方法
    $http_result = curl_exec($http_request);

    $header_size = curl_getinfo($http_request, CURLINFO_HEADER_SIZE);
    $header = substr($http_result, 0, $header_size);
    $header = explode("\r\n", $header);

    $cookie = array();
    foreach ($header as $line) {
        $line = explode(":", $line);
        if ($line[0] == "Set-Cookie") {
            $cookie[] = explode(";", $line[1])[0];
        }
    }
    $cookie = join(";", $cookie);

    $http_request = curl_init();
    $header [] = "Cookie: " . $cookie;
    curl_setopt($http_request, CURLOPT_URL, $url2);
    curl_setopt($http_request, CURLOPT_HTTPHEADER, $header);  // 添加 header 信息
    curl_setopt($http_request, CURLOPT_NOBODY, true);  // 不保留响应内容
    curl_setopt($http_request, CURLOPT_USERAGENT, $user_agent);  // 定义ua
    curl_setopt($http_request, CURLOPT_RETURNTRANSFER, 1);  // 不输出到std
    curl_setopt($http_request, CURLOPT_POST, false);  //使用get方法
    curl_exec($http_request);

    return $cookie;
}
