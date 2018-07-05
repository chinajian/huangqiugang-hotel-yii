<?php
namespace m\controllers;

use Yii;
use yii\web\Controller;


class PublicController extends Controller
{
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
            $redirectUrl = urlencode('http://shop.judanongye.com/wapshop/basic/login-by-wechat.html');
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='. $this->appId .'&redirect_uri='. $redirectUrl .'&response_type=code&scope=snsapi_userinfo&state=888#wechat_redirect';
            $this->redirect($url);
        }else{//#第二步：通过code换取网页授权access_token
            // P($get);
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='. $this->appId .'&secret='. $this->appSecret .'&code='. $get['code'] .'&grant_type=authorization_code';
            $curl = json_decode(Curl::get($url), true);
            if(!array_key_exists('errcode', $curl)){//#第四步：拉取用户信息(需scope为 snsapi_userinfo)
                // P($curl);
                $access_token = $curl['access_token'];
                $openid = $curl['openid'];
                $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='. $access_token .'&openid='. $openid .'&lang=zh_CN';
                $curl = json_decode(Curl::get($url), true);
                // P($curl);

            	/*查询数据库，如果没有此ID，插入数据*/
                // echo $openid;
        		$usersModel = new Users;
	            if($usersModel->login($openid, $curl)){
                    // P('OK');
                    $this->redirect(['index/redirect']);
                }
                Yii::$app->end();
            }
        }
    }

}
