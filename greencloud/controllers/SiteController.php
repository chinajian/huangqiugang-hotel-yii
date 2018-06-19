<?php
namespace frontend\controllers;

use Yii;


class SiteController extends Controller
{


    public function actionIndex()
    {
        return $this->render('index');
    }

   
}
