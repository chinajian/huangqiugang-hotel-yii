<?php
namespace libs;
use Yii;
/*
系统信息存取类
*/
class MInfo
{
    private static $mode = 'seesion';//存储介质
	private static $lifetime = 3600;//存储时间

	private static function setMode()
	{
        self::$mode = Yii::$app->params['saveMode'];
    }

	/*保存登录信息*/
    public static function setLoginInfo($user_id = "", $wechatInfo = "")
    {
    	self::setMode();
        $lifetime = self::$lifetime;
    	if(self::$mode == 'seesion'){
	        $session = Yii::$app->session;
	        session_set_cookie_params($lifetime);
	        $session['m'] = [
                'user_id' => $user_id,
                'wechatInfo' => $wechatInfo,
                'isLogin' => 1,
            ];
    	}
    }

    /*清除登录信息*/
    public static function clearLoginInfo()
    {
    	self::setMode();
    	if(self::$mode == 'seesion'){
	    	Yii::$app->session->removeAll();
    	}
    }


    /*取出登录ID*/
    public static function getUserid()
    {
    	self::setMode();
    	if(self::$mode == 'seesion'){
	    	$session = Yii::$app->session;
	    	if(isset($session['m']['user_id'])){
	            return $session['m']['user_id'];
	        };
    	}
        return "";
    }

    /*取出所有微信授权信息*/
    public static function getWechatInfo()
    {
        self::setMode();
        if(self::$mode == 'seesion'){
            $session = Yii::$app->session;
            if(isset($session['m']["wechatInfo"])){
                return $session['m']["wechatInfo"];
            };
        }
        return "";
    }

    /*取出登录名*/
    public static function getWechatNickname()
    {
        self::setMode();
        if(self::$mode == 'seesion'){
            $session = Yii::$app->session;
            if(isset($session['m']["wechatInfo"]['wechat_nickname'])){
                return urldecode($session['m']["wechatInfo"]['wechat_nickname']);
            };
        }
        return "";
    }

    /*是否登录*/
    public static function getIsLogin()
    {
    	self::setMode();
    	if(self::$mode == 'seesion'){
	    	$session = Yii::$app->session;
	    	if(isset($session['m']['isLogin']) and $session['m']['isLogin'] == 1){
	            return true;
	        };
    	}
        return false;
    }

}