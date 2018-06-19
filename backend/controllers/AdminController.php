<?php

namespace backend\controllers;

use Yii;
use backend\controllers\BasicController;
use app\models\Admin;
use app\models\SysLog;


class AdminController extends BasicController
{
    /**
     * 管理员列表
     */
    public function actionAdminList()
    {
        $get = Yii::$app->request->get();
        if(isset($get['page'])){
            $currPage = (int)$get['page']?$get['page']:1;
        }else{
            $currPage = 1;
        }
    	$Admin = Admin::find();
        $count = $Admin->count();
        $pageSize = Yii::$app->params['pageSize'];
        $adminList = $Admin->select(['username', 'phone', 'email', 'realname', 'lastloginip', 'lastlogin_time', 'issuper', 'creater', 'state', 'add_time', 'logintimes'])->offset($pageSize*($currPage-1))->limit($pageSize)->asArray()->all();
        // P($adminList);
        return json_encode($adminList);
    }

    

}
