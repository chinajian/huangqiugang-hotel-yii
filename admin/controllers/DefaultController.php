<?php

namespace admin\controllers;

use Yii;
use yii\web\Controller;
use admin\controllers\BasicController;


class DefaultController extends BasicController
{
    /**
     * 后台主页面
     */
    public function actionIndex()
    {
    	$this->layout = 'default';
        return $this->render('index');
    }
}
