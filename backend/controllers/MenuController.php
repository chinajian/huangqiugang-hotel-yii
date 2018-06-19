<?php

namespace backend\controllers;

use Yii;
use backend\controllers\BasicController;
use app\models\Menu;
use app\models\SysLog;
use libs\Tools;


class MenuController extends BasicController
{
    /**
     * 获取菜单
     */
    public function actionGetMenuList()
    {
        $menuList = Menu::getTreeList();
        // P($menuList);
        return Tools::showRes(0, $menuList);
    }

    

}