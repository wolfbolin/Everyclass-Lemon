<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/9
 * Time: 12:29
 */
return [
    'Data' => [
        'mission' => [
            'method' => '',
            'scheme'=>'',
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
        'task' => [
            'status' => '',
            'cid' => '',
            'mid' => '',
            'code' => 0,
            'data' => '',
            'time' => 0,
            'user' => ''
        ]
    ],
    'Statistic' => [
        'status_list' => [
            'total_download' => '',
            'stage_download' => '',
            'total_upload' => '',
            'stage_upload' => '',
            'total_success' => '',
            'stage_success' => '',
            'total_error' => '',
            'stage_error' => '',
            'total_user' => '',
            'stage_user' => ''
        ],
        'stage_list' => [
            'stage_download',
            'stage_upload',
            'stage_success',
            'stage_error',
            'stage_user',
        ],
        'task_list' => [
            'total_upload',
            'stage_upload'
        ],
        'check' => [
            'mongodb' => false
        ]
    ]
];