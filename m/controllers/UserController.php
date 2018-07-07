<?php

namespace m\controllers;

use Yii;
use m\controllers\BasicController;
use app\models\User;
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
                    return Tools::showRes(-1, '失败');
                }
            }
            return;
        }

        $user = User::find()->select(['phone', 'wechat_nickname', 'wechat_sex', 'wechat_headimgurl'])->where('user_id = :id', [':id' => $user_id])->asArray()->one();
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
            $post = array(
                'User' => array(
                    'phone' => '13915028703',
                    'password' => '123456',
                )
            );
            // P($post);
            $userModel = new User;
            if($userModel->modUser($post, $user_id, 'binding')){
                return Tools::showRes();
                Yii::$app->end();
            }else{
                if($userModel->hasErrors()){
                    return Tools::showRes(10100, $userModel->getErrors());
                }else{
                    return Tools::showRes(-1, '失败');
                }
            }
            return;
        }
    }
    

}
