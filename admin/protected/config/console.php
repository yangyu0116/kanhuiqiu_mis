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
            'connectionString' => $_SERVER['HOSTNAME']=='jx-vs-video05.jx.baidu.com' ? 'mysql:host=10.65.22.108:3306;dbname=kanhuiqiu' : 'mysql:host=localhost;dbname=kanhuiqiu',
            'emulatePrepare' => true,
            'username' => $_SERVER['HOSTNAME']=='jx-vs-video05.jx.baidu.com' ? 'root' : 'root',
            'password' => $_SERVER['HOSTNAME']=='jx-vs-video05.jx.baidu.com' ? '111111' : '111111',
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
                array(
                    'site_id' => 9,
                    'subchannel' => '短视频节目',
                    'tag' => '郑云',
                ),
                array(
                    'site_id' => 2,
                    'subchannel' => '短视频节目',
                    'tag' => '刘咚咚',
                ),
                array(
                    'site_id' => 2,
                    'subchannel' => '短视频节目',
                    'tag' => '淮秀帮',
                ),
                array(
                    'site_id' => 2,
                    'subchannel' => '短视频节目',
                    'tag' => '胡狼',
                ),
                array(
                    'site_id' => 2,
                    'subchannel' => '短视频节目',
                    'tag' => '暴走漫画',
                ),
                array(
                    'site_id' => 119,
                    'subchannel' => '自制剧',
                    'tag' => '十万个冷笑话',
                ),
                array(
                    'site_id' => 119,
                    'subchannel' => '自制剧',
                    'tag' => '飞碟说',
                ),
                array(
                    'site_id' => 119,
                    'subchannel' => '自制剧',
                    'tag' => '老湿',
                ),
                array(
                    'site_id' => 119,
                    'subchannel' => '自制剧',
                    'tag' => '暴走大事件',
                ),
            ),
            3 => array(
                array(
                    'site_id' => 1,
                    'subchannel' => '自制剧',
                    'tag' => '娱乐猛回头',
                ),
                array(
                    'site_id' => 1,
                    'subchannel' => '自制剧',
                    'tag' => '电视剧有戏',
                ),
                array(
                    'site_id' => 1,
                    'subchannel' => '自制剧',
                    'tag' => '环球影讯',
                ),
                array(
                    'site_id' => 1,
                    'subchannel' => '自制剧',
                    'tag' => '头号人物',
                ),
                array(
                    'site_id' => 1,
                    'subchannel' => '自制剧',
                    'tag' => '青春那些事儿',
                ),
                array(
                    'site_id' => 1,
                    'subchannel' => '自制剧',
                    'tag' => '时尚爆米花',
                ),
                array(
                    'site_id' => 1,
                    'subchannel' => '自制剧',
                    'tag' => '音乐不要停',
                ),
                array(
                    'site_id' => 1,
                    'subchannel' => '自制剧',
                    'tag' => '街拍瞬间',
                ),
                array(
                    'site_id' => 1,
                    'subchannel' => '自制剧',
                    'tag' => '帕帕帮',
                ),
                array(
                    'site_id' => 1,
                    'subchannel' => '自制剧',
                    'tag' => '以德服人',
                ),
                array(
                    'site_id' => 1,
                    'subchannel' => '自制剧',
                    'tag' => '综艺大嘴巴',
                ),
                array(
                    'site_id' => 1,
                    'subchannel' => '自制剧',
                    'tag' => '笑霸来了',
                ),
                array(
                    'site_id' => 1,
                    'subchannel' => '自制剧',
                    'tag' => '圈里圈外',
                ),
            ),
        ),
    ),
);