<?php

namespace m\controllers;

use Yii;
use m\controllers\BasicController;
use libs\MInfo;


class UserController extends BasicController
{
    /**
     * 个人信息
     */
    public function actionUser()
    {
    	header('Access-Control-Allow-Origin:*');
        return MInfo::getLoginName($access['nickname']);;
    }

    

}
