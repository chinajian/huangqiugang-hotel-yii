<?php

namespace m\controllers;

use Yii;
use m\controllers\BasicController;
use app\models\User;
use app\models\Room;
use app\models\OrderInfo;
use libs\MInfo;
use libs\Tools;
use \Curl\Curl;


class HotelController extends BasicController
{
    private $appKey = '10003';
    private $appSecret = '8b3d727f1ba1cde61cef63143ebab5e5';
    private $hotelGroupCode = 'GCBZG';
    private $v = '3.0';
    private $format = 'json';
    private $local = 'zh_CN';
    private $hotelGroupId = 73;
    private $hotelId = 94;
    private $sessionid = '';

    /*
    计算签名
    $param      参数
    $sign       返回值
    */
    private function computeSign($param)
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

    /*登录*/
    public function init()
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
        $param['sign'] = $this->computeSign($param);

        $curl = new Curl();
        $curl->post(Yii::$app->params['api']['login'], $param);
        if ($curl->error) {
            echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            $this->sessionid = $curl->response->resultInfo;
        }
    }

    /*2.1-查询酒店信息*/
    public function actionHotels()
    {
        $param = array(
            'appKey' => $this->appKey,
            'sessionId' => $this->sessionid,
            'hotelGroupCode' => $this->hotelGroupCode,
        );
        $param['sign'] = $this->computeSign($param);
        // P($param);

        $curl = new Curl();
        $curl->post(Yii::$app->params['api']['hotel'].'hotels', $param);
        if ($curl->error) {
            echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            P(json_decode($curl->response));
        }
    }

    /*2.2-查询房型列表*/
    public function actionRoomList()
    {
        $param = array(
            'appKey' => $this->appKey,
            'sessionId' => $this->sessionid,
            'hotelGroupId' => $this->hotelGroupId,
            'hotelId' => $this->hotelId,
        );
        $param['sign'] = $this->computeSign($param);

        $curl = new Curl();
        $curl->post(Yii::$app->params['api']['hotel'].'roomList', $param);
        if ($curl->error) {
            echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            P(json_decode($curl->response));
        }
    }

    /*2.3-查询房价房量(配备本地数据库的数据，完善房间信息)*/
    public function actionQueryHotelList($date = '', $dayCount = 1)
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $date = isset($post['date'])?$post['date']:'';
            $dayCount = isset($post['dayCount'])?$post['dayCount']:1;
        };
        if(empty($date)){
            $date = date('Y-m-d', time());//到店日期
        };
        if(empty($dayCount)){
            $dayCount = 1;//入住天数
        }
        // P($date . '*' . $dayCount);
        if(empty($date)){
            return Tools::showRes(10300, '参数有误误！');
            Yii::$app->end();
        };
        $param = array(
            'appKey' => $this->appKey,
            'sessionId' => $this->sessionid,
            'date' => $date,//到店日期
            'dayCount' => $dayCount,//入住天数
            'cityCode' => 'HZZJ1',//城市代码（1-查询酒店信息接口中获取cityCode）
            'rateCodes' => 'WKI',//指定房价码
            'salesChannel' => 'WEB',//渠道代码
            'hotelIds' => $this->hotelId,
            'hotelGroupId' => $this->hotelGroupId,
        );
        $param['sign'] = $this->computeSign($param);
        // P($param);

        $curl = new Curl();
        $curl->post(Yii::$app->params['api']['hotel'].'queryHotelList', $param);
        if ($curl->error) {
            echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            // P($curl->response);
            // P(json_decode($curl->response));
            // P(json_decode($curl->response)->hrList[0]->roomList);
            $roomList = json_decode($curl->response)->hrList[0]->roomList;
            $roomModel = new Room;
            if(!empty($roomList)){
                foreach($roomList as $k => $v){
                    $roomList[$k]->info = $roomModel->getRoomInfo($v->rmtype);
                };
            }
            // P($roomList);
            if(empty($roomList)){
                return Tools::showRes(0, []);
            }else{
                return Tools::showRes(0, $roomList);
            }
        }
    }

    /*调取actionQueryHotelList接口，然后匹配出房间详情*/
    public function actionRoom($ratecode = '', $rmtype = '', $date = '', $dayCount = 1)
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
        };
        if(empty($ratecode)){
            $ratecode = (isset($post['ratecode'])?$post['ratecode']:"");
        };
        if(empty($rmtype)){
            $rmtype = (isset($post['rmtype'])?$post['rmtype']:"");
        };
        if(empty($date)){
            $date = (isset($post['date'])?$post['date']:date('Y-m-d', time()));
        };
        // P($date . '-' . $dayCount);
        if(empty($ratecode) or empty($rmtype) or empty($date)){
            return Tools::showRes(10300, '参数有误！');
            Yii::$app->end();
        };
        $res = json_decode($this->actionQueryHotelList($date, $dayCount));
        if($res->code == 0){
            $roomList = $res->msg;
        }else{
            $roomList = [];
        }
        // P($roomList);
        $room = [];
        if($roomList){
            foreach($roomList as $k => $v){
                if(($v->ratecode == $ratecode) and ($v->rmtype == $rmtype)){
                    $room = $v;
                    break;
                }
            }
        }
        // P($room);
        return Tools::showRes(0, $room);
    }
    
    /*2.7-创建订单*/
    private function actionBook($post)
    {
        $param = array(
            'appKey' => $this->appKey,
            'sessionId' => $this->sessionid,
            'hotelGroupId' => $this->hotelGroupId,
            'hotelId' => $this->hotelId,
            'arr' => $post['arr'],//到达日期
            'dep' => $post['dep'],//离开日期
            'rmtype' => $post['rmtype'],//房型
            'rateCode' => $post['rateCode'],//指定房价码（3-查询房价房量,rateCodes）
            'rmNum' => $post['rm_num'],//房数
            'rsvMan' => $post['rsv_man'],//预订人
            'sex' => 2,//性别
            'mobile' => $post['mobile'],//联系电话
            'idType' => '',//证件类型
            'idNo' => '',//证件号码
            'email' => '',//邮箱
            'cardType' => '',//会员计划
            'cardNo' => '',//会员卡号
            'adult' => $post['adult'],//人数
            'remark' => '',//备注
            'salesChannel' => '',//销售渠道
            'src' => 'WEB',//来源
            'rsvType' => '002',//预定类型   002 (现付订单)  007 (预付订单)
            'market' => 'WEB',//市场
            'channel' => 'WEB',//渠道
            'packages' => '',//包价
            'everyDayRate' => '',//每日房价
            'rsvManId' => '',//订房人id
            'rsvCompanyId' => '',//订房单位id
        );
        $param['sign'] = $this->computeSign($param);

        $curl = new Curl();
        $curl->post(Yii::$app->params['api']['hotel'].'book', $param);
        if ($curl->error) {
            // echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
            return Tools::showRes(-1, $curl);
        } else {
            // P($curl->response);
            return Tools::showRes(0, $curl->response);
        }
    }

    /*创建订单(先本地创建，然后调取接口)*/
    public function actionAddOrder()
    {
        /*插入订单*/
        if(Yii::$app->request->isPost){
        // if(1){
            $post = Yii::$app->request->post();
            // $post = array(
            //     'arr' => '2018-07-23 08:08:08',//到达日期
            //     'dep' => '2018-07-25 17:08:08',//离开日期
            //     'rmtype' => 'DXT',//房型
            //     'rateCode' => 'MEM',//指定房价码
            //     'rm_num' => 1,//房数
            //     'rsv_man' => '小玉',//预订人
            //     'mobile' => '13915028703',//联系电话
            //     'adult' => 2,//人数
            //     'remarks' => "啊沙发沙发斯蒂芬",//备注
            // );
            $post['arr'] = $post['arr'].' 00:00:00';
            $post['dep'] = $post['dep'].' 23:59:59';
            // $post['rateCode'] = 'MEM';
            $rateCode = $post['rateCode'];
            $rmtype = isset($post['rmtype'])?$post['rmtype']:'';
            $dayCount = date('d', strtotime($post['dep'])) - date('d', strtotime($post['arr']));//入住天数
            // P($rateCode . '-' . $post['rmtype'] . '-' . $post['arr'] . '-' . $dayCount);
            $data = array(
                'OrderInfo' => $post
            );
            $room = $this->actionRoom($rateCode, $rmtype, $post['arr'], $dayCount);//获取房间信息,主要是1、判断是否存在此房间；2、获取价格
            $room = json_decode($room);//详情信息
            // P($room);
            if($room->code != 0 or empty($room->msg)){
                return Tools::showRes(20001, '此房型不存在');
            }else{
                $data['OrderInfo']['price'] = $room->msg->rate1;
                // P(json_decode($room)->msg->rate1);
            }
            $data['OrderInfo']['arr'] = strtotime($data['OrderInfo']['arr']);
            $data['OrderInfo']['dep'] = strtotime($data['OrderInfo']['dep']);
            if($data['OrderInfo']['arr'] < strtotime(date('Y-m-d', time()))){
                return Tools::showRes(20002, '到达时间不能小于当前时间');
            }
            if($data['OrderInfo']['arr'] > $data['OrderInfo']['dep']){
                return Tools::showRes(20003, '到达时间不能大于离开时间');
            }
            // P($data);
            $orderInfoModel = new OrderInfo;
            
            $data['OrderInfo']['user_id'] = MInfo::getUserid();
            if($order_id = $orderInfoModel->addOrder($data)){
                // P($order_id);
                /*通知接口*/
                $res = json_decode($this->actionBook($post));
                // P($res);
                if($res->code == 0){
                    // echo $res->msg->crsNo;
                    /*完善 中央预定号*/
                    // $orderInfoModel->setCrsNo($order_id, $res->msg->crsNo);
                    if($res->msg->crsNo != ""){
                        $orderInfoModel->updateAll(['crs_no' => $res->msg->crsNo], 'order_id = :order_id', [':order_id' => $order_id]);
                    }
                }

                return Tools::showRes(0, $order_id);
                Yii::$app->end();
            }else{
                if($orderInfoModel->hasErrors()){
                    return Tools::showRes(10100, $orderInfoModel->getErrors());
                }else{
                    return Tools::showRes(-1);
                }
            }
        }else{
            return Tools::showRes(10300, '参数有误！');
            Yii::$app->end();
        }
    }

}
