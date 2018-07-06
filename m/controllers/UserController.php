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
        // if(Yii::$app->request->isPost){
        if(1){
            $post = Yii::$app->request->post();
            $post = array(
                'User' => array(
                    'wechat_sex' => 2,
                )
            );
            P($post);
            $userModel = new User;
            if($userModel->saveUser($post)){
                return Tools::showRes();
                Yii::$app->end();
            }else{
                if($userModel->hasErrors()){
                    return showRes2(300, $userModel->getErrors());
                }else{
                    return showRes2(300, '修改失败');
                }
            }
            return;
        }

        $user = User::find()->select(['phone', 'wechat_nickname', 'wechat_sex', 'wechat_headimgurl'])->where('user_id = :id', [':id' => $user_id])->asArray()->one();
        // P($user);
        return Tools::showRes(0, $user);

    }

    

}
