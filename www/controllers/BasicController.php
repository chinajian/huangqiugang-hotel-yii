<?php
namespace www\controllers;

use Yii;
use yii\web\Controller;
use libs\WwwInfo;
use libs\Tools;


class BasicController extends Controller
{


	public function beforeAction($action)
    {

        /*验证登录*/
        if(!WwwInfo::getIsLogin()){
            // header('location:index.php?r=/site/login');
            $this->redirect(array('/site/login'));
            Yii::$app->end();
        }
        return true;
    }

}
