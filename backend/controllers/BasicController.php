<?php

namespace backend\controllers;

use yii;
use yii\web\Controller;
use libs\AdminInfo;
use libs\Tools;

class BasicController extends Controller
{
    public $layout = flase;

    public function beforeAction($action)
    {
        header('Access-Control-Allow-Credentials:true');
        header('Access-Control-Allow-Origin:http://localhost:8080');
        header('Access-Control-Allow-Methods:POST,GET');
        /*验证登录*/
        if(!AdminInfo::getIsLogin()){
            echo Tools::showRes(10405, '请登录系统');
            Yii::$app->end();
        }
        return true;
    }

}
