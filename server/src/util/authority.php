<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/7
 * Time: 14:11
 */

namespace WolfBolin\Slim\Authority;

use Slim\Http\Request;
use Slim\Http\Response;

use WolfBolin\Slim\HTTP as WolfBolin_HTTP;

function x_auth_token() {
    $result = function (Request $request, Response $response, $next) {
        $auth_token = $request->getHeader('X-Auth-Token');
        if ($auth_token) {
            if (count($auth_token) == 1 && $auth_token[0] == $this->get('Auth_Token')) {
                return $next($request, $response);
            } else {
                return WolfBolin_HTTP\Unauthorized3($response);
            }
        } else {
            return WolfBolin_HTTP\Unauthorized($response);
        }
    };
    return $result;
}
