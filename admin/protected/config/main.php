<?php

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => '看会球管理后台',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'kanhuiqiu',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array($_SERVER['REMOTE_ADDR']),
        ),
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        // uncomment the following to enable URLs in path-format
        /*
          'urlManager'=>array(
          'urlFormat'=>'path',
          'rules'=>array(
          '<controller:\w+>/<id:\d+>'=>'<controller>/view',
          '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
          '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
          ),
          ),
         */

        'db' => array(
            'class' => 'application.extensions.PHPPDO.CPdoDbConnection',
            'pdoClass' => 'PHPPDO',
            'connectionString' => $_SERVER['HTTP_HOST']=='mis.kanhuiqiu.com' ? 'mysql:host=127.0.0.1:3306;dbname=kanhuiqiu' : 'mysql:host=127.0.0.1;dbname=kanhuiqiu',
            'emulatePrepare' => true,
            'username' => $_SERVER['HTTP_HOST']=='mis.kanhuiqiu.com' ? 'video' : 'video',
            'password' => $_SERVER['HTTP_HOST']=='mis.kanhuiqiu.com' ? 'video' : 'video',
            'charset' => 'utf8',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
    ),
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'yangyu@sina.cn',
        'siteAPI' => array(
            //'56.com' => 'http://api.56.com/api/bdvideo.php?url=',
        ),
    ),
);









