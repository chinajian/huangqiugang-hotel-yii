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
        // MInfo::setLoginInfo('零度 火焰');//存入登录信息
        /*验证登录*/
        if(!MInfo::getIsLogin()){
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='. Yii::$app->params['appId'] .'&redirect_uri='. $redirectUrl .'&response_type=code&scope=snsapi_userinfo&state=888#wechat_redirect';
            echo Tools::showRes(10405, '请登录系统', $url);
            Yii::$app->end();
        }
        // $session = Yii::$app->session;
        // P($session['wapshop']);
        return true;
    }

}
