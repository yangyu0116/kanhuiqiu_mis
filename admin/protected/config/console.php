<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Console Application',
    // preloading 'log' component
    'preload' => array('log'),
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    // application components
    'components' => array(
        'db' => array(
            'class' => 'application.extensions.PHPPDO.CPdoDbConnection',
            'pdoClass' => 'PHPPDO',
            'connectionString' => $_SERVER['HOSTNAME']=='jx-vs-video05.jx.baidu.com' ? 'mysql:host=127.0.0.1:3306;dbname=kanhuiqiu' : 'mysql:host=localhost;dbname=kanhuiqiu',
            'emulatePrepare' => true,
            'username' => $_SERVER['HOSTNAME']=='jx-vs-video05.jx.baidu.com' ? 'video' : 'video',
            'password' => $_SERVER['HOSTNAME']=='jx-vs-video05.jx.baidu.com' ? 'video' : 'video',
            'charset' => 'utf8',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
    ),
    'params' => array(
        'siteWhiteList' => array(),
        'siteSubmission' => array(
            5 => array(
                array(
                    'site_id' => 9,
                    'subchannel' => '短视频节目',
                    'tag' => '胥渡吧',
                ),
            ),
            3 => array(
                array(
                    'site_id' => 1,
                    'subchannel' => '自制剧',
                    'tag' => '娱乐猛回头',
                ),
            ),
        ),
    ),
);