<?php

namespace admin\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use admin\controllers\BasicController;
use app\models\Room;
use app\models\SysLog;
use \Curl\Curl;


class RoomController extends BasicController
{

    private $appKey = '10003';
    private $appSecret = '8b3d727f1ba1cde61cef63143ebab5e5';
    private $hotelGroupCode = 'GCBZG';
    private $v = '3.0';
    private $format = 'json';
    private $local = 'zh_CN';
    private $hotelGroupId = 2;
    private $hotelId = 9;
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


    /*同步接口*/
    public function actionSynchronous()
    {
        /*2.2-查询房型列表*/
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
            // P(json_decode($curl->response));
            $res = json_decode($curl->response);
            if($res->resultCode == 0 and !empty($res->result)){
                $roomTypeList = $res->result;
                foreach($roomTypeList as $k => $v){
                    $roomType = $v->roomType;//得到房型
                    /*查看数据库中是否有此房型，如果有，则忽略，如果没有，需要添加一个*/
                    $roomModel = new Room;
                    $room = Room::find()->where('room_type = :roomType', [':roomType' => $roomType])->one();
                    if(empty($room)){
                        $data = array(
                            "Room" => array(
                                "room_type" => $roomType
                            )
                        );
                        $roomModel->addRoom($data);
                    }
                }
                return showRes(200, '同步成功', 'refresh');
                Yii::$app->end();

            }
        }
    }


    /**
     * 客房列表
     */
    public function actionRoomList()
    {
        $get = Yii::$app->request->get();
        if(isset($get['page'])){
            $currPage = (int)$get['page']?$get['page']:1;
        }else{
            $currPage = 1;
        }
        $roomModel = Room::find();
        $count = $roomModel->count();
        $pageSize = Yii::$app->params['pageSize'];
        $roomList = $roomModel->offset($pageSize*($currPage-1))->limit($pageSize)->asArray()->all();
        // P($roomList);
        
    	return $this->render('roomList', [
            'roomList' => $roomList,
            'get' => $get,
    		'pageInfo' => [
                'count' => $count,
                'currPage' => $currPage,
                'pageSize' => $pageSize,
            ]
    	]);
    }


    /*
    修改客房
    */
    public function actionModRoom()
    {
        /*如果有数据，进行修改*/
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $id = (int)(isset($post['Room']['room_id'])?$post['Room']['room_id']:0);
            if(!$id){
                return showRes(300, '参数有误！');
                Yii::$app->end();
            }
            $roomModel = new Room;
            if($roomModel->modRoom($id, $post)){
                return showRes(200, '修改成功', 'back');
                Yii::$app->end();
            }else{
                if($roomModel->hasErrors()){
                    return showRes(300, $roomModel->getErrors());
                }else{
                    return showRes(300, '修改失败');
                }
            }
            return;
        }

        $get = Yii::$app->request->get();
        $id = (int)(isset($get['room_id'])?$get['room_id']:0);
        if(!$id){
            return showRes(300, '参数有误！');
            Yii::$app->end();
        }
        $room = Room::find()->where('room_id = :id', [':id' => $id])->asArray()->one();
        if(!empty($room['albums'])){
            $room['albums'] = explode(',', $room['albums']);
        }else{
            $room['albums'] = [];
        }
        // P($room);

    	return $this->render('modRoom', [
            'room' => $room
        ]);

    }

}
