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
            $post = Yii::$app->request->post();
            // $post = array(
            //     'User' => array(
            //         'user_name' => 'jiang',
            //     )
            // );
            // P($post);
            $user_id = MInfo::getUserid();
            // echo $user_id;
            $userModel = new User;
            if($userModel->saveUser($post, $user_id)){
                return Tools::showRes();
                Yii::$app->end();
            }else{
                if($userModel->hasErrors()){
                    return showRes(10100, $userModel->getErrors());
                }else{
                    return showRes(-1, '失败');
                }
            }
            return;
        }

        $user = User::find()->select(['phone', 'wechat_nickname', 'wechat_sex', 'wechat_headimgurl'])->where('user_id = :id', [':id' => $user_id])->asArray()->one();
        // P($user);
        return Tools::showRes(0, $user);
    }

    

}
