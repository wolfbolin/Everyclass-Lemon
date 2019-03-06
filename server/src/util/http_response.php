<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/6
 * Time: 19:36
 * @param $response
 * @return mixed
 */
namespace WolfBolin\Slim\HTTP;

function Not_modified($response) {
    return $response->withStatus(304);
}

function Bad_request($response, $info="访问参数异常") {
    $error_info = ["status" => "error", "info" => "$info"];
    return $response->withStatus(403)->withJson($error_info);
}

function Not_found($response, $info="未找到指定的文档") {
    $error_info = ["status" => "error", "info" => "$info"];
    return $response->withStatus(404)->withJson($error_info);
}


