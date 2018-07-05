<?php
namespace m\controllers;

use Yii;
use yii\web\Controller;
use \Curl\Curl;


class PublicController extends Controller
{
    
	/*默认路由方法*/
    public function actionIndex()
    {
        echo '默认路由方法';
    }


    /*微信登录*/
    public function actionLoginByWechat()
    {
        $get = Yii::$app->request->get();
        if(!array_key_exists('code', $get)){//#第一步：用户同意授权，获取code
            $redirectUrl = urlencode('http://m.api.ghchotel.com/index.php?r=/public/login-by-wechat');
            $state = urlencode('http://local.www.judanongye.com/index.html');
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='. Yii::$app->params['appId'] .'&redirect_uri='. $redirectUrl .'&response_type=code&scope=snsapi_userinfo&state='. isset($get['state'])?$get['state']:$state .'#wechat_redirect';
            $this->redirect($url);
        }else{//#第二步：通过code换取网页授权access_token
            // P($get);
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='. Yii::$app->params['appId'] .'&secret='. Yii::$app->params['appSecret'] .'&code='. $get['code'] .'&grant_type=authorization_code';
            $curl = new Curl();
    		$access = json_decode($curl->get($url), true);
    		// P($access);
            if(!array_key_exists('errcode', $access)){//#第四步：拉取用户信息(需scope为 snsapi_userinfo)
                $access_token = $access['access_token'];
                $openid = $access['openid'];
                $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='. $access_token .'&openid='. $openid .'&lang=zh_CN';
                $userinfo = json_decode($curl->get($url), true);
    			// P($userinfo);

                MInfo::setLoginInfo($access['nickname']);//存入登录信息
                // return Tools::showRes();//登录成功
                $this->redirect($get['state']);

            	/*查询数据库，如果没有此ID，插入数据*/
    			// echo $openid;
    			// $usersModel = new Users;
    			// if($usersModel->login($openid, $curl)){
    			// 	P('OK');
    			// 	$this->redirect(['index/redirect']);
    			// }
            }else{
    			echo $access['errmsg'];
    		}
            Yii::$app->end();
        }
    }

}
