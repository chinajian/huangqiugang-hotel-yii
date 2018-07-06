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
        header('Access-Control-Allow-Origin:http://local.www.judanongye.com');
        header('Access-Control-Allow-Methods:POST,GET');

        // MInfo::setLoginInfo(1, $data['wechat_nickname']);//存入登录信息

        /*验证登录*/
        if(!MInfo::getIsLogin()){
            $url = 'http://m.api.ghchotel.com/index.php?r=/public/login-by-wechat';
            echo Tools::showRes(10405, '请登录系统', $url);
            Yii::$app->end();
        }
        // $session = Yii::$app->session;
        // P($session['wapshop']);
        return true;
    }

}
