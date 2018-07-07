<?php
namespace m\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use \Curl\Curl;
use libs\MInfo;


class PublicController extends Controller
{
    
	/*默认路由方法*/
    public function actionIndex()
    {
        echo '默认路由方法';

        $userinfo = array(
            'openid' => 'ovx7C1LGqg1CjMhtNkMXmOd0VbLo',
            'nickname' => '零度 火焰',
            'sex' => '1',
            'language' => 'zh_CN',
            'city' => '常州',
            'province' => '江苏',
            'country' => '中国',
            'headimgurl' => 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLmtjiaJlQy9E2rc4nSwnrG7jNLsXcRKte4picCKPibLYtp4VwvBp7hxQG6946JibZqMHXoGVbRWkpx7Q/132',
            'privilege' => Array()
        );
        // $userModel = new User;
        // $data['User']['wechat_openid'] = isset($userinfo['openid'])?$userinfo['openid']:'';
        // $data['User']['wechat_nickname'] = isset($userinfo['nickname'])?urlencode($userinfo['nickname']):'';
        // $data['User']['wechat_headimgurl'] = isset($userinfo['headimgurl'])?$userinfo['headimgurl']:'';
        // $data['User']['wechat_sex'] = isset($userinfo['sex'])?$userinfo['sex']:'';
        // $data['User']['wechat_country'] = isset($userinfo['country'])?$userinfo['country']:'';
        // $data['User']['wechat_province'] = isset($userinfo['province'])?$userinfo['province']:'';
        // $data['User']['wechat_city'] = isset($userinfo['city'])?$userinfo['city']:'';
        // $data['User']['regby'] = 3;//3-通过微信注册
        // $data['User']['reg_time'] = time();
        // // P($data);
        // if($userModel->login($data)){
        //     // $this->redirect($get['state']);
        // }
    }


    /*微信登录*/
    public function actionLoginByWechat()
    {
        $get = Yii::$app->request->get();
        if(!array_key_exists('code', $get)){//#第一步：用户同意授权，获取code
            $redirectUrl = urlencode('http://m.api.ghchotel.com/index.php?r=/public/login-by-wechat');
            // $state = isset($get['state'])?urlencode($get['state']):urlencode('http://local.www.judanongye.com/index.html');
            $state = isset($get['state'])?urlencode($get['state']):urlencode('http://10.9.87.104/#/');
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='. Yii::$app->params['appId'] .'&redirect_uri='. $redirectUrl .'&response_type=code&scope=snsapi_userinfo&state='. $state .'#wechat_redirect';
            //P($url);            
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

                // MInfo::setLoginInfo($userinfo['nickname']);//存入登录信息
                // return Tools::showRes();//登录成功
                // $this->redirect($get['state']);

            	/*查询数据库，如果没有此ID，插入数据*/
    			// echo $openid;
    			$userModel = new User;
                $data['User']['wechat_openid'] = isset($userinfo['openid'])?$userinfo['openid']:'';
                $data['User']['wechat_nickname'] = isset($userinfo['nickname'])?urlencode($userinfo['nickname']):'';
                $data['User']['wechat_headimgurl'] = isset($userinfo['headimgurl'])?$userinfo['headimgurl']:'';
                $data['User']['wechat_sex'] = isset($userinfo['sex'])?$userinfo['sex']:'';
                $data['User']['wechat_country'] = isset($userinfo['country'])?$userinfo['country']:'';
                $data['User']['wechat_province'] = isset($userinfo['province'])?$userinfo['province']:'';
                $data['User']['wechat_city'] = isset($userinfo['city'])?$userinfo['city']:'';
                $data['User']['regby'] = 3;//3-通过微信注册
                $data['User']['reg_time'] = time();
    			if($userModel->login($data)){
    				$this->redirect($get['state']);
    			}
            }else{
    			echo $access['errmsg'];
    		}
            Yii::$app->end();
        }
    }

}
