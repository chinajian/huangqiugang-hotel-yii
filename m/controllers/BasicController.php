<?php
namespace m\controllers;

use Yii;
use yii\web\Controller;
use libs\MInfo;
use libs\Tools;


class BasicController extends Controller
{


	public function beforeAction($action)
    {
        header('Access-Control-Allow-Credentials:true');
        header('Access-Control-Allow-Origin:http://m.ghchotel.com');
        // header('Access-Control-Allow-Origin:http://10.9.87.104:3000');
        header('Access-Control-Allow-Methods:POST,GET');

        // MInfo::setLoginInfo(2, '%E5%87%8C%E4%B9%B1%E7%9A%84%E5%8D%B7%E6%AF%9B%E6%80%AA');//存入登录信息

        /*验证登录*/
        if(!MInfo::getIsLogin()){
            $url = 'http://m.api.ghchotel.com/index.php?r=/public/login-by-wechat';
            echo Tools::showRes(10405, '请登录系统'.MInfo::getIsLogin(), $url);
            Yii::$app->end();
        }
        // $session = Yii::$app->session;
        // P($session['wapshop']);
        return true;
    }

}
