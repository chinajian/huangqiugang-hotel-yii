<?php
namespace m\controllers;

use Yii;
use yii\web\Controller;
use \Curl\Curl;


class PublicController extends Controller
{
	private $appId = 'wxd1bbfb1cd92a2ff5';
    private $appSecret = 'c93a80cb6746631a4ab3020abcce5fd0';

    
	/*默认路由方法*/
    public function actionIndex()
    {
        echo 'ddd';
    }


    /*微信登录*/
    public function actionLoginByWechat()
    {
        $get = Yii::$app->request->get();
        if(!array_key_exists('code', $get)){//#第一步：用户同意授权，获取code
            $redirectUrl = urlencode('http://m.api.ghchotel.com/index.php?r=/public/login-by-wechat');
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='. $this->appId .'&redirect_uri='. $redirectUrl .'&response_type=code&scope=snsapi_userinfo&state=888#wechat_redirect';
            $this->redirect($url);
        }else{//#第二步：通过code换取网页授权access_token
            // P($get);
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='. $this->appId .'&secret='. $this->appSecret .'&code='. $get['code'] .'&grant_type=authorization_code';
        $curl = new Curl();    
	$access = json_decode($curl->get($url), true);
	//echo '<pre>';
	//print_r($access);
	//echo '</pre>';
            if(!array_key_exists('errcode', $access)){//#第四步：拉取用户信息(需scope为 snsapi_userinfo)
                //Tools::P($curl);
                $access_token = $access['access_token'];
                $openid = $access['openid'];
                $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='. $access_token .'&openid='. $openid .'&lang=zh_CN';
                $userinfo = json_decode($curl->get($url), true);
                //Tools::P($curl);
		echo '<pre>';
		print_r($userinfo);
		echo '</pre>';

            	/*查询数据库，如果没有此ID，插入数据*/
                // echo $openid;
        	//	$usersModel = new Users;
	        //    if($usersModel->login($openid, $curl)){
                    // P('OK');
                //    $this->redirect(['index/redirect']);
                //}
                Yii::$app->end();
            }else{
		echo $access['errmsg'];
		}
        }
    }

}
