<?php
namespace greencloud\controllers;

use Yii;
use yii\web\Controller;

class BasicController extends Controller
{
	protected $appKey = '10003';
	protected $appSecret = '8b3d727f1ba1cde61cef63143ebab5e5';
	protected $hotelGroupCode = 'GCBZG';
	protected $v = '3.0';
	protected $format = 'json';
	protected $local = 'zh_CN';
	protected $hotelGroupId = 2;
	protected $hotelId = 9;

    /*
    计算签名
    $param 		参数
    $sign 		返回值
    */
    protected function computeSign($param)
    {
    	$sign = '';
    	if($param){
		    ksort($param, SORT_STRING);
		    foreach($param as $k => $v){
		    	$sign .= $k.$v;
		    }
		    $sign = $this->appSecret.$sign.$this->appSecret;
		    $sign = sha1($sign);
		    $sign = strtoupper($sign);
    	}
    	return $sign;
    }
   
}
