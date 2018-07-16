<?php
namespace libs;
use Yii;
/*
系统信息存取类
*/
class WwwInfo
{
    private static $mode = 'seesion';//存储介质
	private static $lifetime = 3600;//存储时间

	private static function setMode()
	{
        self::$mode = Yii::$app->params['saveMode'];
    }

	/*保存登录信息*/
    public static function setLoginInfo($user_id, $wechat_nickname)
    {
    	self::setMode();
        $lifetime = self::$lifetime;
    	if(self::$mode == 'seesion'){
	        $session = Yii::$app->session;
	        session_set_cookie_params($lifetime);
	        $session['www'] = [
                'user_id' => $user_id,
	            'wechat_nickname' => $wechat_nickname,
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
	    	if(isset($session['www']['user_id'])){
	            return urldecode($session['www']['user_id']);
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
            if(isset($session['www']['wechat_nickname'])){
                return urldecode($session['www']['wechat_nickname']);
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
	    	if(isset($session['www']['isLogin']) and $session['www']['isLogin'] == 1){
	            return true;
	        };
    	}
        return false;
    }

}