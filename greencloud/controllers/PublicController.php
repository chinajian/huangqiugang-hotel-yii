<?php
namespace greencloud\controllers;

use Yii;
use greencloud\controllers\BasicController;
use \Curl\Curl;
use libs\Tools;
use libs\GreencloudInfo;

class PublicController extends BasicController
{
	/*登录*/
	public function actionLogin()
    {
    	$param = array(
		    'appKey' => $this->appKey,
		    'v' => $this->v,
		    'format' => $this->format,
		    'local' => $this->local,
		    'hotelGroupCode' => $this->hotelGroupCode,
		    'method' => 'user.login',
		    'usercode' => 'gcbzg0',
		    'password' => '89kjanJD1k02b'
    	);
    	Tools::p($this->computeSign($param));
    	$param['sign'] = $this->computeSign($param);

    	$curl = new Curl();
		$curl->post(Yii::$app->params['api']['login'], $param);
		if ($curl->error) {
		    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
		} else {
		    $sessionid = $curl->response->resultInfo;
		    GreencloudInfo::setLoginInfo($sessionid);//存入登录信息
		}
    }


    public function actionGetSessionid()
    {
    	echo GreencloudInfo::getSessionid();
    }
   
}
