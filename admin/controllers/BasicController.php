<?php

namespace admin\controllers;

use yii;
use yii\web\Controller;
use libs\AdminInfo;
use app\models\Album;


class BasicController extends Controller
{
    public $layout = 'default';
    // public $enableCsrfValidation = false;

    public function beforeAction($action)
    {
        /*验证登录*/
        if(!AdminInfo::getIsLogin()){
            $this->redirect(['public/login']);
            Yii::$app->end();
        }

        /*取出菜单*/
        $this->view->params['menu'] = [
            array(
                "name" => '系统设置',
                "icon" => 'cog',
                "m" => 'Admin',
                "c" => 'manager',
                "a" => 'manager-list',
                "data" => '',
                "children" => [
                    array(
                        "name" => '系统设置',
                        "icon" => '',
                        "m" => 'Admin',
                        "c" => 'set_sys',
                        "a" => 'index',
                        "data" => '',
                    ),array(
                        "name" => '管理员列表',
                        "icon" => '',
                        "m" => 'Admin',
                        "c" => 'manager',
                        "a" => 'manager-list',
                        "data" => '',
                    ),array(
                        "name" => '操作日志',
                        "icon" => '',
                        "m" => 'Admin',
                        "c" => 'syslog',
                        "a" => 'index',
                        "data" => '',
                    ),
                ]
            ),
            array(
                "name" => '客房设置',
                "icon" => 'map-marker',
                "m" => 'Admin',
                "c" => 'room',
                "a" => 'room-list',
                "data" => '',
                "children" => [
                    // array(
                    //     "name" => '商铺分类',
                    //     "icon" => '',
                    //     "m" => 'Admin',
                    //     "c" => 'room_category',
                    //     "a" => 'category-list',
                    //     "data" => '',
                    // ),
                    array(
                        "name" => '客房列表',
                        "icon" => '',
                        "m" => 'Admin',
                        "c" => 'room',
                        "a" => 'room-list',
                        "data" => '',
                    )
                ]
            ),
            array(
                "name" => '周边推荐',
                "icon" => 'th-list',
                "m" => 'Admin',
                "c" => 'article',
                "a" => 'article-list',
                "data" => '',
                "children" => [
                    array(
                        "name" => '推荐分类',
                        "icon" => '',
                        "m" => 'Admin',
                        "c" => 'article_category',
                        "a" => 'category-list',
                        "data" => '',
                    ),
                    array(
                        "name" => '推荐列表',
                        "icon" => '',
                        "m" => 'Admin',
                        "c" => 'article',
                        "a" => 'article-list',
                        "data" => '',
                    )
                ]
            ),
            array(
                "name" => '精彩活动',
                "icon" => 'heart',
                "m" => 'Admin',
                "c" => 'activity',
                "a" => 'activity-list',
                "data" => '',
                "children" => [
                    array(
                        "name" => '活动列表',
                        "icon" => '',
                        "m" => 'Admin',
                        "c" => 'activity',
                        "a" => 'activity-list',
                        "data" => '',
                    )
                ]
            ),
            array(
                "name" => '商务中心',
                "icon" => 'globe',
                "m" => 'Admin',
                "c" => 'business',
                "a" => 'business-list',
                "data" => '',
                "children" => [
                    array(
                        "name" => '商务中心列表',
                        "icon" => '',
                        "m" => 'Admin',
                        "c" => 'business',
                        "a" => 'business-list',
                        "data" => '',
                    )
                ]
            ),
            array(
                "name" => '积分商品',
                "icon" => 'compressed',
                "m" => 'Admin',
                "c" => 'goods',
                "a" => 'goods-list',
                "data" => '',
                "children" => [
                    array(
                        "name" => '商品列表',
                        "icon" => '',
                        "m" => 'Admin',
                        "c" => 'goods',
                        "a" => 'goods-list',
                        "data" => '',
                    )
                ]
            ),
        ];
        return true;
    }

    /*图片上传*/
    public function actionUploadFile()
    {
        $post = Yii::$app->request->post();
        // P($post);
        if($_FILES and $post['name']){//有图片上传
            $albumModel = new Album();
            if($fileName = $albumModel->upload($post)){
                return showRes(200, $fileName);
            }else{
                return showRes(300, $albumModel->getErrors());
            }
            return;
        }
        return showRes(300, '没有传图片！');
    }

}
