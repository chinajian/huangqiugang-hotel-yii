<?php

namespace m\controllers;

use Yii;
use yii\web\Controller;
use app\models\Msg;
use libs\Tools;
use \Curl\Curl;


class MsgController extends Controller
{
    private $url = 'http://api.chanzor.com';
    private $account = '98acfa';
    private $password = '3tvnwyr5dk';
    private $tpl = '您的短信验证码是{code}如非本人操作，请忽略此短信。本短信免费。【环球港邮轮酒店】';

    public function beforeAction($action)
    {
        $origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';
        $allow_origin = array(  
            'http://www.ghchotel.com',  
            'http://m.ghchotel.com'  
        );
        if(in_array($origin, $allow_origin)){  
            header('Access-Control-Allow-Origin:'.$origin);  
            header('Access-Control-Allow-Methods:POST,GET'); 
            header('Access-Control-Allow-Credentials:true'); 
        } 
        // header('Access-Control-Allow-Credentials:true');
        // header('Access-Control-Allow-Origin:http://www.ghchotel.com');
        // header('Access-Control-Allow-Origin:http://m.ghchotel.com');
        // header('Access-Control-Allow-Origin:http://10.9.87.104:3000');
        // header('Access-Control-Allow-Methods:POST,GET');
        return true;
    }

    /*
    发送注册验证短信
    $type 1-注册绑定 2-PC登录
    */
    public function actionSendReg($type = 1)
    {
        if(Yii::$app->request->isPost){
        // if(1){
            $post = Yii::$app->request->post();
            // $post = array(
            //     'mobile' => '13915028703',
            // );
            if(!isset($post['mobile']) or empty($post['mobile'])){
                return Tools::showRes(10300, '参数有误！');
                Yii::$app->end();
            }
            /*60S内不能重复发送*/
            if(!$this->checkRepeat($post['mobile'])){
                return Tools::showRes(10501, '60秒内不能重复发送');
                Yii::$app->end();
            };
            $code = mt_rand(1000, 9999);
            $content = str_replace("{code}", $code, $this->tpl);
            $curl = new Curl();
            $params = array(
                'account' => $this->account,
                'password' => md5($this->password),
                'mobile' => $post['mobile'],
                'content' => $content,
                'sendTime' => date('Y-m-d H:i:s', time()),
                'extno' => '',
            );
            $curl->post($this->url.'/send', $params);
            if ($curl->error) {
                echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
            } else {
                // P($curl->response);
                /*入库*/
                $data = array(
                    'Msg' => array(
                        'mobile' => $post['mobile'],
                        'code' => (string)$code,
                        'content' => $content,
                        'send_time' => time(),
                        'task_id' => (string)$curl->response->taskId,
                        'overage' => (string)$curl->response->overage,
                        'mobile_count' => $curl->response->mobileCount,
                        'status' => (string)$curl->response->status,
                        'desc' => $curl->response->desc,
                        'is_use' => 0,
                        'type' => $type,//注册验证码
                    )
                );
                $msgModel = new Msg;
                if($msgModel->addMsg($data)){
                    return Tools::showRes();
                    Yii::$app->end();
                }else{
                    if($msgModel->hasErrors()){
                        return Tools::showRes(10100, $msgModel->getErrors());
                    }else{
                        return Tools::showRes(-1);
                    }
                }
            }
        }
    }

    /*
    验证规定时间内，是否可以发送
    $mobile     手机号
    $s          时间（秒）默认60s
    $type       类型，默认是1-注册
    */
    private function checkRepeat($mobile, $s = 60, $type = 1){
        $time = time() - $s;
        $msg = Msg::find()->where('mobile = :mobile and type = :type and send_time > :time and is_use = 0', [':mobile' => $mobile, ':type' => $type, ':time' => $time])->one();
        if(empty($msg)){
            return true;
        }
        return false;
    }

}
