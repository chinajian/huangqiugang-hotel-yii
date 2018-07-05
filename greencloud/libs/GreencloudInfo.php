<?php
namespace libs;
use Yii;
/*
系统信息存取类
*/
class GreencloudInfo
{
    private static $mode = 'file';//存储介质 seesion
	private static $lifetime = 3600;//存储时间

	private static function setMode()
	{
        self::$mode = Yii::$app->params['saveMode'];
    }

	/*保存登录信息*/
    public static function setLoginInfo($sessionid)
    {
    	self::setMode();
        $lifetime = self::$lifetime;
        if(self::$mode == 'seesion'){
            $session = Yii::$app->session;
            session_set_cookie_params($lifetime);
            $session['greencloud'] = [
                'sessionid' => $sessionid,
                'isLogin' => 1,
            ];
        }
        if(self::$mode == 'file'){
            $myfile = fopen("sessionid.txt", "w") or die("Unable to open file!");
            $txt = $sessionid;
            fwrite($myfile, $txt);
            fclose($myfile);
        }
    }

    /*清除登录信息*/
    public static function clearLoginInfo()
    {
    	self::setMode();
    	if(self::$mode == 'seesion'){
	    	Yii::$app->session->removeAll();
    	}
        if(self::$mode == 'file'){
            
        }
    }


    /*取出sessionid*/
    public static function getSessionid()
    {
    	self::setMode();
    	if(self::$mode == 'seesion'){
	    	$session = Yii::$app->session;
	    	if(isset($session['greencloud']['sessionid'])){
	            return $session['greencloud']['sessionid'];
	        };
    	}
        if(self::$mode == 'file'){
            if (file_exists('sessionid.txt')) {
                $myfile = fopen("sessionid.txt", "r") or die("Unable to open file!");
                $res = fread($myfile, filesize("sessionid.txt"));
                fclose($myfile);
                return $res;
            }
        }
        return "";
    }


}