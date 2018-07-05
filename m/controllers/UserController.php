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
        return MInfo::getLoginName($access['nickname']);;
    }

    

}
