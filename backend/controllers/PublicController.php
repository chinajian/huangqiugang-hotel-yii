<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
// use models\Admin;
use app\models\Admin;
use libs\AdminInfo;
use libs\Tools;


class PublicController extends Controller
{
    public $layout = flase;
    public $enableCsrfValidation = false;
    /**
     * 登录
     */
    public function actionLogin()
    {
        header('Access-Control-Allow-Credentials:true');
        header('Access-Control-Allow-Origin:http://localhost:8080');
        header('Access-Control-Allow-Methods:POST,GET');
        if(Yii::$app->request->isPost){
    		$post = Yii::$app->request->post();
            // P($post);
            // $post['Admin']['username'] = 'admin';
            // $post['Admin']['password'] = '123456';
    		$admin = new Admin;
    		if($admin->login($post)){
                $session = Yii::$app->session;
                return Tools::showRes();//登录成功
                Yii::$app->end();
            }else{
                if($admin->hasErrors()){
                    return Tools::showRes(10100, $admin->getErrors());
                }
            }
        }
        if(AdminInfo::getIsLogin()){
            return Tools::showRes();//已经登录
            Yii::$app->end();
        }
    	return Tools::showRes(10300, '参数有误');
    }

    /*
    登出
    */
    public function actionLogout()
    {
        AdminInfo::clearLoginInfo();
        if(!AdminInfo::getIsLogin()){
            return Tools::showRes(0, '登出成功');
            Yii::$app->end();
        };
        return Tools::showRes(10404, '登出失败');
    }

}
