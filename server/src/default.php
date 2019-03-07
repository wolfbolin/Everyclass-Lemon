<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/3
 * Time: 0:26
 */
//Configuration
return [
    'Auth_Token' => 'everyclass_token',
    'MongoDB' => [
        'db' => '',
        'host' => '',
        'port' => '',
        'username' => '',
        'password' => '',
        'authSource' => ''
    ],
    'Data' => [
        'mission' => [
            'method' => '',
            'host' => '',
            'path' => '',
            'header' => [],
            'param' => [],
            'data' => '',
            'target' => 0,
            'download' => 0,
            'upload' => 0,
            'success' => 0,
            'error' => 0
        ],
        'cookie' => [
            'cookie' => '',
            'time' => 0,
            'download' => 0,
            'upload' => 0,
            'success' => 0,
            'error' => 0
        ],
        'receipt' => [
            'status' => '',
            'cid' => '',
            'mid' => '',
            'code' => 0,
            'data' => '',
            'time' => 0,
            'user' => ''
        ]
    ]
];
