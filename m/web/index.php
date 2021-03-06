<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../config/main.php',
    require __DIR__ . '/../config/main-local.php'
);

require_once(__DIR__ . '/../config/constant.php');//引入常量

/*打印*/
if(!function_exists('P')){
    function P($arr, $die = true)
    {
        echo "<pre>";
        print_r($arr);
        // var_dump($arr);
        echo "</pre>";
        if($die){
        	die;
        }
    }
}

(new yii\web\Application($config))->run();
