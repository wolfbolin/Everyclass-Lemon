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

use Slim\Http\Response;

function Not_modified(Response $response)
{
    return $response->withStatus(304);
}

function Bad_request(Response $response, $info = "访问参数异常")
{
    $error_info = ["status" => "error", "info" => "$info"];
    return $response->withStatus(403)->withJson($error_info);
}

function Not_found(Response $response, $info = "未找到指定的文档")
{
    $error_info = ["status" => "error", "info" => "$info"];
    return $response->withStatus(404)->withJson($error_info);
}

function Unauthorized(Response $response, $info = "需要验证身份")
{
    $error_info = ["status" => "error", "info" => "$info"];
    return $response->withStatus(401)->withJson($error_info);
}

function Unauthorized3(Response $response, $info = "身份验证失败")
{
    $error_info = ["status" => "error", "info" => "$info"];
    return $response->withStatus(401)->withJson($error_info);
}
