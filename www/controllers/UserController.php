<?php

namespace www\controllers;
use Yii;
use www\controllers\BasicController;
use app\models\User;
use app\models\Msg;
use app\models\Room;
use app\models\OrderInfo;
use libs\WwwInfo;
use libs\Tools;


class UserController extends BasicController
{
    /**
     * 个人信息
     */
    public function actionUser()
    {
        $user_id = WwwInfo::getUserid();

        /*如果有数据，进行修改*/
        if(Yii::$app->request->isPost){
        // if(1){
            $post = Yii::$app->request->post();
            // $post = array(
            //     'User' => array(
            //         'user_name' => '电信',
            //     )
            // );
            // P($post);
            $userModel = new User;
            if($userModel->modUser($post, $user_id)){
                return Tools::showRes();
                Yii::$app->end();
            }else{
                if($userModel->hasErrors()){
                    return Tools::showRes(10100, $userModel->getErrors());
                }else{
                    return Tools::showRes(-1);
                }
            }
        }

        $user = User::find()->select(['phone', 'wechat_nickname', 'wechat_sex', 'wechat_headimgurl', 'curr_integrals', 'pay_integrals'])->where('user_id = :id', [':id' => $user_id])->asArray()->one();
        $user['wechat_nickname'] = urldecode($user['wechat_nickname']);
        // P($user);
        return Tools::showRes(0, $user);
    }

    /*
    我的订单
    $status     订单状态 空-全部 1未付款, 2已付款, 3已取消, 4-待入住, 5-待评价, 6-完成
    */
    public function actionMyOrder($status = '')
    {
        $get = Yii::$app->request->get();
        if(isset($get['page'])){
            $currPage = (int)$get['page']?$get['page']:1;
        }else{
            $currPage = 1;
        }
        if(!empty($status)){
            $andWhere = "order_status = " . (int)$status;
        }else{
            $andWhere = "";
        };
        $orderInfoModel = OrderInfo::find();
        $count = $orderInfoModel->count();
        $pageSize = Yii::$app->params['pageSize'];
        $orderInfoList = $orderInfoModel->select(['order_id', 'rsv_man', 'mobile', 'rm_num', 'adult', 'order_sn', 'order_status', 'rateCode', 'rmtype', 'price', 'remarks', 'arr', 'dep', 'add_time'])->where('user_id =' . WwwInfo::getUserid())->where($andWhere)->offset($pageSize*($currPage-1))->limit($pageSize)->asArray()->all();
        $roomModel = new Room;
        foreach($orderInfoList as $k => $v){
            $orderInfoList[$k]['arr'] = date("Y-m-d H:i:s", $v['arr']);
            $orderInfoList[$k]['dep'] = date("Y-m-d H:i:s", $v['dep']);
            $orderInfoList[$k]['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
            $orderInfoList[$k]['info'] = $roomModel->getRoomInfo($v['rmtype']);
        }
        // P($orderInfoList);
        return Tools::showRes(0, $orderInfoList);
    }

    /*订单详情*/
    public function actionOrderDetail($order_id = 0)
    {
        /*如果有数据*/
        $get = Yii::$app->request->get();
        // $get = array(
        //     "order_id" => 1
        // );
        // P($get);
        $order_id = (int)(isset($get['order_id'])?$get['order_id']:0);
        if(!$order_id){
            return Tools::showRes(10300, '参数有误！');
            Yii::$app->end();
        }
        /*根据索引，取出对应的价格区间*/
        $order_info = OrderInfo::find()->joinWith('room')->where('order_id = :id', [':id' => $order_id])->andWhere('user_id =' . WwwInfo::getUserid())->asArray()->one();
        if(empty($order_info)){
            return Tools::showRes(10300, '没有此订单');
            Yii::$app->end();
        };

        $order_info["room_name"] = $order_info["room"]["room_name"];
        $order_info["arr"] = date("Y-m-d H:i:s", $order_info["arr"]);
        $order_info["dep"] = date("Y-m-d H:i:s", $order_info["dep"]);
        $order_info["add_time"] = date("Y-m-d H:i:s", $order_info["add_time"]);
        unset($order_info["user_id"]);
        unset($order_info["pay_id"]);
        unset($order_info["pay_time"]);
        unset($order_info["room"]);
        // P($order_info);
        return Tools::showRes(0, $order_info);
    }


    /*取消订单*/
    public function actionCancelOrder()
    {
        /*如果有数据，进行修改*/
        if(Yii::$app->request->isPost){
        // if(1){
            $post = Yii::$app->request->post();
            // $post = array(
            //     "order_id" => 1
            // );
            // P($post);
            $order_id = (int)(isset($post['order_id'])?$post['order_id']:0);
            if(!$order_id){
                return Tools::showRes(10300, '参数有误！');
                Yii::$app->end();
            }
            /*根据索引，取出对应的价格区间*/
            $order_info = OrderInfo::find()->where('order_id = :id', [':id' => $order_id])->andWhere('user_id =' . WwwInfo::getUserid())->one();
            if(empty($order_info)){
                return Tools::showRes(10300, '没有此订单');
                Yii::$app->end();
            }
            if($order_info->order_status > 1){
                return Tools::showRes(10300, '只有未支付的订单才可以取消');
                Yii::$app->end();
            };
            /*修改数据*/
            $order_info->order_status = 3;
            if($order_info->save(false)){
                return Tools::showRes();
            };
        }
        return Tools::showRes(10300, '参数有误！');
    }

}
