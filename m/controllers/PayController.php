<?php

namespace m\controllers;
use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\OrderInfo;
// use app\models\PayLog;
use yii\helpers\Html;
use libs\MInfo;


class PayController extends Controller
{
	public $layout = false;
    public $enableCsrfValidation = false;
    private $openId = '';
    
    /*订单支付*/
    public function actionPayOrder()
    {
        header('Access-Control-Allow-Origin:*');
        $user_id = MInfo::getUserid();
        $post = Yii::$app->request->post();
        // $post = array(
        //     'order_id' => 8,//订单ID
        // );
        // P($post);

        $order_id = (int)(isset($post['order_id'])?$post['order_id']:0);
        if(!$order_id){
            return Tools::showRes(10300, '参数有误！');
            Yii::$app->end();
        }

        $orderInfo = OrderInfo::find()->where('order_id = :id', [':id' => $order_id])->one();
        if(empty($orderInfo)){
            return Tools::showRes(60001, '订单ID不存在');
            Yii::$app->end();
        }

        $user = User::find()->where('user_id = ' . $orderInfo->user_id)->one();
        $this->openId = $user->wechat_openid;
        

        return $this->getJsApiParameters($orderInfo['price'], $orderInfo['order_sn']);

    }

    /*
    获取jsApiParameters
    $money      支付金额
    $ordersn    订单号
    */
    private function getJsApiParameters($money, $ordersn='')
    {
        require_once "../../components/WxpayAPI/lib/WxPay.Api.php";
        require_once "../../components/WxpayAPI/example/WxPay.JsApiPay.php";
        require_once '../../components/WxpayAPI/example/log.php';
        
        $money = (int)($money*100);//转换成分
        if(empty($ordersn)){
            $ordersn = \WxPayConfig::MCHID.date("YmdHis");
        }

        //初始化日志
        $logHandler= new \CLogFileHandler("../../components/WxpayAPI/logs/".date('Y-m-d').'.log');
        $log = \Log::Init($logHandler, 15);

        //①、获取用户openid
        $tools = new \JsApiPay();
        // $openId = $tools->GetOpenid();
        $openId = $this->openId;
        // echo $openId;

        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("环球港酒店-客房预订");//商品描述
        // $input->SetAttach("test");
        $input->SetOut_trade_no($ordersn);//商户订单号
        $input->SetTotal_fee($money);//支付金额
        $input->SetTime_start(date("YmdHis"));//发起时间
        $input->SetTime_expire(date("YmdHis", time() + 600));//失效时间
        // $input->SetGoods_tag("test");
        $input->SetNotify_url("http://m.api.ghchotel.com/index.php?r=/pay/backnotify");//回调地址
    
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);

        $order = \WxPayApi::unifiedOrder($input);

        $jsApiParameters = $tools->GetJsApiParameters($order);


        return $jsApiParameters; 
    }


    /*
    购买回调函数
    仅限微信支付
    */
    public function actionBacknotify()
    {
        //获取通知的数据  
        $postStr = file_get_contents("php://input");
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

        if ($postObj === false) {
            die('parse xml error');
        }
        if ($postObj->return_code != 'SUCCESS') {
            die("error_code: ".$postObj->err_code.",msg: ".$postObj->return_msg);
        }
        
        /*更改订单状态*/
        $orderInfo = OrderInfo::find()->where('order_sn = :sn', [':sn'=>$postObj->out_trade_no])->one();
        if(!empty($orderInfo)){
            $orderInfo->pay_status = 2;
            $orderInfo->pay_id = 3;
            $orderInfo->pay_name = '微信支付';
            $orderInfo->money_paid = bcdiv($postObj->total_fee, 100, 2);//分转成元;

            $orderInfo->pay_time = time();
            $orderInfo->save(false);
        }

        /*取出会员信息*/
        $user = User::find()->where('wechat_openid = :openid', [':openid'=>$postObj->openid])->one();
        if(!empty($user)){
            /*记录支付日志*/
            $PayLog = new PayLog();
            $PayLog->addLog($user->user_id, $postObj);
        }


        return '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
    }


    /*支付测试*/
    public function actionPayTest()
    {
        // header('Access-Control-Allow-Origin:*');
        // $uid = WapshopInfo::getUserId();
        // $post = Yii::$app->request->post();
        // $money = (int)(isset($post['money'])?$post['money']:0);
        // if(!$money){
        //     return showRes2(302, '参数有误！');
        //     Yii::$app->end();
        // }

        require_once "../../components/WxpayAPI/lib/WxPay.Api.php";
        require_once "../../components/WxpayAPI/example/WxPay.JsApiPay.php";
        require_once '../../components/WxpayAPI/example/log.php';

        //初始化日志
        $logHandler= new \CLogFileHandler("../../components/WxpayAPI/logs/".date('Y-m-d').'.log');
        $log = \Log::Init($logHandler, 15);

        //打印输出数组信息
        function printf_info($data)
        {
            foreach($data as $key=>$value){
                echo "<font color='#00ff55;'>$key</font> : $value <br/>";
            }
        }

        //①、获取用户openid
        $tools = new \JsApiPay();
        // $openId = $tools->GetOpenid();
        $openId = 'ovx7C1LGqg1CjMhtNkMXmOd0VbLo';
        echo $openId;

        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("test");
        $input->SetAttach("test");
        $input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url("http://m.api.ghchotel.com/../../components/WxpayAPI/example/notify.php");
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        // $input->SetAppid(WxPayConfig::APPID);
        // $input->SetMch_id(WxPayConfig::MCHID);
        $order = \WxPayApi::unifiedOrder($input);
        echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
        printf_info($order);
        $jsApiParameters = $tools->GetJsApiParameters($order);

        //获取共享收货地址js函数参数
        $editAddress = $tools->GetEditAddressParameters();

        //③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
        /**
         * 注意：
         * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
         * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
         * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
         */

        return $this->renderFile('./pay_test.html',[
            'jsApiParameters'=> $jsApiParameters,
            'editAddress'=> $editAddress
        ]);   
    }


}
