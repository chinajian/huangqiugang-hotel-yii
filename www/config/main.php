<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);
$db = require __DIR__ . '/db.php';

return [
    'id' => 'app-www',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'www\controllers',
    'defaultRoute' => 'site/index',//默认路由(没有写路由数据的情况下)
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-www',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-www', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the www
            'name' => 'advanced-www',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
        'db' => $db,
    ],
    'aliases' => [  
        '@libs' => '@app/libs'
    ],
    'params' => $params,
];
