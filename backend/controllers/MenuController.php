<?php

namespace backend\controllers;

use Yii;
use backend\controllers\BasicController;
use app\models\Menu;
use app\models\SysLog;
use libs\Tools;


class MenuController extends BasicController
{
    /*
    菜单列表
    */
    public function actionMenuList($flag = '')
    {
        $menuList = [];
        if(empty($flag)){//平级列表（菜单中用到）
            $menuList = Menu::getTreeList();
        }
        if($flag == 'tree'){//上下级关系,子集存在child中（vue tree组件中用到）
            $menuList = Menu::getVueTreeList();
        }
        // P($menuList);
        return Tools::showRes(0, $menuList);
    }

    /*添加菜单*/
    public function actionAddMenu($id = 0)
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            // P($post);
            if(isset($post['Menu']['display']) and $post['Menu']['display'] == true){
                $post['Menu']['display'] = 1;
            }else{
                $post['Menu']['display'] = 0;
            }
            $menuModel = new Menu;
            if($menuModel->addMenu($post)){
                return Tools::showRes();
                Yii::$app->end();
            }else{
                if($menuModel->hasErrors()){
                    return Tools::showRes(10100, $menuModel->getErrors());
                }else{
                    return Tools::showRes(-1);
                }
            }
        }
    }

    /*修改菜单*/
    public function actionModMenu()
    {
        $get = Yii::$app->request->get();
        $id = (int)(isset($get['id'])?$get['id']:0);
        if($id == 0){
            return Tools::showRes(10300, '参数有误！');
            Yii::$app->end();
        }
        /*如果有数据，进行修改*/
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if(isset($post['Menu']['display']) and $post['Menu']['display'] == 'true'){
                $post['Menu']['display'] = 1;
            }else{
                $post['Menu']['display'] = 0;
            }
            // P($post);
            $menuModel = new Menu;
            if($menuModel->modMenu($id, $post)){
                return Tools::showRes();
                Yii::$app->end();
            }else{
                if($menuModel->hasErrors()){
                    return Tools::showRes(300, $menuModel->getErrors());
                }else{
                    return Tools::showRes(-1);
                }
            }
            return;
        }

        $menu = Menu::find()->where('id = :id', [':id' => $id])->asArray()->one();
        // P($menu);
        return Tools::showRes(0, $menu);
    }

    /*
    删除
    */
    public function actionDelMenu()
    {
        $get = Yii::$app->request->get();
        $id = (int)(isset($get['id'])?$get['id']:0);
        if($id == 0){
            return Tools::showRes(10300, '参数有误！');
            Yii::$app->end();
        }
        $menu = Menu::find()->where('id = :id', [':id' => $id])->one();
        if(!empty($menu) and $menu->delete()){
            /*写入日志*/

            return Tools::showRes();
            Yii::$app->end();
        }else{
            return Tools::showRes(-1);
            Yii::$app->end();
        }
    }

}