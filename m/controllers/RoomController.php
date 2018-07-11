<?php

namespace m\controllers;

use Yii;
use m\controllers\BasicController;
use app\models\Room;
use libs\MInfo;
use libs\Tools;


class RoomController extends BasicController
{
    /**
     * 房间列表
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
        $roomList = $roomModel->select(['room_name', 'rate1', 'adv_min', 'adv_max', 'stay_min', 'stay_max', 'preview', 'desc',])->offset($pageSize*($currPage-1))->limit($pageSize)->asArray()->all();
        // P($roomList);
        return Tools::showRes(0, $roomList);
    }

    /*房间详情*/
    public function actionRoom()
    {
        $get = Yii::$app->request->get();
        $id = (int)(isset($get['id'])?$get['id']:0);
        if($id == 0){
            return Tools::showRes(10300, '参数有误！');
            Yii::$app->end();
        }
        $room = Room::find()->where('room_id = :id', [':id' => $id])->asArray()->one();
        // P($room);
        return Tools::showRes(0, $room);
    }

    

}
