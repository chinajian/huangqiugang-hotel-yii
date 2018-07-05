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
    public function actionAddMenu()
    {
        $post = Yii::$app->request->post();
        P($post);
    }

    /*修改菜单*/
    public function actionModMenu()
    {
        $post = Yii::$app->request->post();
        P($post);
    }

}