<?php

namespace m\controllers;
use Yii;
use m\controllers\BasicController;
use app\models\User;
use app\models\Msg;
use app\models\Room;
use app\models\OrderInfo;
use libs\MInfo;
use libs\Tools;


class UserController extends BasicController
{
    /**
     * 个人信息
     */
    public function actionUser()
    {
        $user_id = MInfo::getUserid();

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

        $user = User::find()->select(['phone', 'wechat_nickname', 'wechat_sex', 'wechat_headimgurl'])->where('user_id = :id', [':id' => $user_id])->asArray()->one();
        $user['wechat_nickname'] = urldecode($user['wechat_nickname']);
        // P($user);
        return Tools::showRes(0, $user);
    }

    /*微信账号 绑定 手机号*/
    public function actionBinding()
    {
        $user_id = MInfo::getUserid();
        if(Yii::$app->request->isPost){
        // if(1){
            $post = Yii::$app->request->post();
            // $post = array(
            //     'User' => array(
            //         'phone' => '13915028703',
            //         'password' => '123456',
            //         'code' => '8417',
            //     )
            // );
            // P($post);
            if(!isset($post['User']['code']) or empty($post['User']['code'])){
                return Tools::showRes(10300, '参数有误！');
                Yii::$app->end();
            }
            /*验证短信码*/
            $msg = Msg::find()->where('mobile = :phone and type = 1 and code = :code and is_use = 0', [':phone' => $post['User']['phone'], ':code' => $post['User']['code']])->one();
            if(empty($msg)){
                return Tools::showRes(10502, '无效的验证码');
                Yii::$app->end();
            }
            $time = time() - 5*60;//5分钟内有效
            if($time > $msg['send_time']){
                return Tools::showRes(10503, '此验证码已过期');
                Yii::$app->end();
            }
            $userModel = new User;

            $transaction = Yii::$app->db->beginTransaction();//事物处理
            try{
                /*绑定会员*/
                if(!$userModel->modUser($post, $user_id, 'binding')){
                    if($userModel->hasErrors()){
                        return Tools::showRes(10100, $userModel->getErrors());
                    }else{
                        return Tools::showRes(-1);
                    }
                }

                /*验证码更改为已使用*/
                $msg->is_use = 1;
                $msg->save(false);

                $transaction->commit();
                return Tools::showRes();
            }catch(\Exception $e){
                $transaction->rollback();
                return Tools::showRes(10200, '异常信息：'.$e->getMessage().'异常文件：'.$e->getFile().'异常所在行：'.$e->getLine().'异常码：'.$e->getCode());
            };
        }
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
        $orderInfoList = $orderInfoModel->select(['order_id', 'rsv_man', 'mobile', 'rm_num', 'adult', 'order_sn', 'order_status', 'rateCode', 'rmtype', 'price', 'remarks', 'arr', 'dep', 'add_time'])->where('user_id =' . MInfo::getUserid())->where($andWhere)->offset($pageSize*($currPage-1))->limit($pageSize)->asArray()->all();
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
        $order_info = OrderInfo::find()->where('order_id = :id', [':id' => $order_id])->andWhere('user_id =' . MInfo::getUserid())->asArray()->one();
        if(empty($order_info)){
            return Tools::showRes(10300, '没有此订单');
            Yii::$app->end();
        };
        unset($order_info["user_id"]);
        unset($order_info["pay_id"]);
        unset($order_info["add_time"]);
        unset($order_info["pay_time"]);
        $order_info["arr"] = date("Y-m-d H:i:s");
        $order_info["dep"] = date("Y-m-d H:i:s");
        // P($order_info);
        return Tools::showRes(0, $order_info);
    }


    /*取消订单*/
    public function actionCancelOrder($order_id = 0)
    {
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
        $order_info = OrderInfo::find()->where('order_id = :id', [':id' => $order_id])->andWhere('user_id =' . MInfo::getUserid())->one();
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
        return;
    }

}
