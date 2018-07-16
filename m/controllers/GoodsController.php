<?php

namespace m\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use app\models\Goods;
use yii\helpers\Html;
use libs\Tools;


class GoodsController extends BasicController
{
    /**
     * 商品列表
     */
    public function actionGoodsList()
    {
        $get = Yii::$app->request->get();
        if(isset($get['page'])){
            $currPage = (int)$get['page']?$get['page']:1;
        }else{
            $currPage = 1;
        }
        $goodsModel = Goods::find();
        $count = $goodsModel->count();
        $pageSize = Yii::$app->params['pageSize'];
        $goodsList = $goodsModel->select(["goods_id", "goods_name", "goods_sn", "goods_brief", "album_img", "integral"])->offset($pageSize*($currPage-1))->limit($pageSize)->orderBy(['goods_id' => SORT_DESC])->asArray()->all();
        // P($goodsList);
        return Tools::showRes(0, $goodsList);
    }


}
