<?php

namespace admin\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use admin\controllers\BasicController;
use app\models\Goods;
use app\models\SysLog;
use yii\helpers\Html;


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
        $goodsList = $goodsModel->offset($pageSize*($currPage-1))->limit($pageSize)->orderBy(['goods_id' => SORT_DESC])->asArray()->all();
        return $this->render('goodsList', [
            'goodsList' => $goodsList,
            'pageInfo' => [
                'count' => $count,
                'currPage' => $currPage,
                'pageSize' => $pageSize,
            ]
        ]);
    }

    /**
     * 添加商品
     */
    public function actionAddGoods()
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            // P($post);
            $goodsModel = new Goods;
            if($goodsModel->addGoods($post)){
                return showRes(200, '添加成功', Url::to(['goods/add-goods']));
                // return showRes(200, '添加成功');
                Yii::$app->end();
            }else{
                if($goodsModel->hasErrors()){
                    return showRes(300, $goodsModel->getErrors());
                }else{
                    return showRes(300, '添加失败');
                }
            }
            return showRes(399, '参数有误！');
        }
        return $this->render('addGoods');
    }

    /*
    修改商品
    */
    public function actionModGoods()
    {
        /*如果有数据，进行修改*/
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $id = (int)(isset($post['Goods']['goods_id'])?$post['Goods']['goods_id']:0);
            if(!$id){
                return showRes(300, '参数有误！');
                Yii::$app->end();
            }
            $goodsModel = new Goods;
            if($goodsModel->modGoods($id, $post)){
                // return showRes(200, '修改成功', 'back');
                return showRes(200, '修改成功');
                Yii::$app->end();
            }else{
                if($goodsModel->hasErrors()){
                    return showRes(300, $goodsModel->getErrors());
                }else{
                    return showRes(300, '修改失败');
                }
            }
            return;
        }

        $get = Yii::$app->request->get();
        $id = (int)(isset($get['id'])?$get['id']:0);
        if(!$id){
            return showRes(300, '参数有误！');
            Yii::$app->end();
        }

        $goods = Goods::find()->where('goods_id = :id', [':id' => $id])->asArray()->one();
        $goods['album_img'] = !empty($goods['album_img'])?explode(',', $goods['album_img']):[];
        $goods['goods_desc'] = Html::decode($goods['goods_desc']);


        return $this->render('modGoods', [
            'goods' => $goods
        ]);
    }

    /*
	删除商品
    */
    public function actionDelGoods()
    {
    	$post = Yii::$app->request->post();
        $id = (int)(isset($post['id'])?$post['id']:0);
        if(!$id){
            return showRes(300, '参数有误！');
            Yii::$app->end();
        }
        $goods = Goods::find()->where('goods_id = :id', [':id' => $id])->one();
        if($goods and $goods->delete()){
            /*写入日志*/
            SysLog::addLog('删除商品['. $goods->goods_name .']成功');

            return showRes(200, '删除成功', Url::to(['goods/goods-list']));
            Yii::$app->end();
        }else{
            return showRes(300, '删除失败');
            Yii::$app->end();
        }
    }


}
