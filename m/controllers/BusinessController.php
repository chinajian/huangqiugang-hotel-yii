<?php

namespace m\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use app\models\Business;
use yii\helpers\Html;
use libs\Tools;


class BusinessController extends Controller
{
    public function beforeAction($action)
    {
        header('Access-Control-Allow-Credentials:true');
        header('Access-Control-Allow-Origin:http://m.ghchotel.com');
        // header('Access-Control-Allow-Origin:http://10.9.87.104:3000');
        header('Access-Control-Allow-Methods:POST,GET');
        return true;
    }


    /**
     * 活动列表
     */
    public function actionBusinessList()
    {
        $get = Yii::$app->request->get();
        if(isset($get['page'])){
            $currPage = (int)$get['page']?$get['page']:1;
        }else{
            $currPage = 1;
        }
        $businessModel = Business::find();
        $count = $businessModel->count();
        $pageSize = Yii::$app->params['pageSize'];
        $businessList = $businessModel->offset($pageSize*($currPage-1))->limit($pageSize)->asArray()->all();
        foreach($businessList as $k => $v){
            if(!empty($businessList[$k]['thumb'])){
                $businessList[$k]['thumb'] = SITE_URL.ltrim($businessList[$k]['thumb'], "./");
                $tmpArr = explode('uploads', $businessList[$k]['thumb']);
                $businessList[$k]['thumb'] = $tmpArr[0].'uploads'.Tools::getImgBySize($tmpArr[1]);//mini图 转化成 预览图
            };

            unset($businessList[$k]["cat_id"]);
            unset($businessList[$k]["copyfrom"]);
            unset($businessList[$k]["keywords"]);
            unset($businessList[$k]["business_type"]);
            unset($businessList[$k]["status"]);
            unset($businessList[$k]["link"]);
            unset($businessList[$k]["readpoint"]);
            unset($businessList[$k]["sort"]);
            unset($businessList[$k]["last_modify_time"]);
            unset($businessList[$k]["add_time"]);
            unset($businessList[$k]["author"]);
            unset($businessList[$k]["content"]);

        }
        // P($businessList);
        return Tools::showRes(0, [
            'businessList' => $businessList,
            'pageInfo' => [
                'count' => $count,
                'currPage' => $currPage,
                'pageSize' => $pageSize,
                'totalPage' => ceil($count/$pageSize),
            ]
        ]);
    }


    /*详情*/
    public function actionBusinessDetail()
    {
        $get = Yii::$app->request->get();
        $id = (int)(isset($get['id'])?$get['id']:0);
        if(!$id){
            return Tools::showRes(300, '参数有误！');
            Yii::$app->end();
        }
        $business = Business::find()->where('id = :id', [':id' => $id])->asArray()->one();
        $business['content'] = Html::decode($business['content']);

        if(!empty($business['thumb'])){
            $business['thumb'] = SITE_URL.ltrim($business['thumb'], "./");
            $tmpArr = explode('uploads', $business['thumb']);
            $business['thumb'] = $tmpArr[0].'uploads'.Tools::getImgBySize($tmpArr[1], 'big');//mini图 转化成 预览图
            $business['thumb'] = [$business['thumb']];
        }

        if(!empty($business['author'])){
            $business['author'] = explode(" ", $business['author']);
        };

        unset($business["cat_id"]);
        unset($business["copyfrom"]);
        unset($business["keywords"]);
        unset($business["business_type"]);
        unset($business["status"]);
        unset($business["link"]);
        unset($business["readpoint"]);
        unset($business["sort"]);
        unset($business["last_modify_time"]);
        unset($business["add_time"]);
        unset($business["author"]);

        // P($business);
        return Tools::showRes(0, [
            'business' => $business
        ]);
    }

}
