<?php

namespace m\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use app\models\Activity;
use yii\helpers\Html;
use libs\Tools;


class ActivityController extends Controller
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
    public function actionActivityList()
    {
        $get = Yii::$app->request->get();
        if(isset($get['page'])){
            $currPage = (int)$get['page']?$get['page']:1;
        }else{
            $currPage = 1;
        }
        $activityModel = Activity::find();
        $count = $activityModel->count();
        $pageSize = Yii::$app->params['pageSize'];
        $activityList = $activityModel->offset($pageSize*($currPage-1))->limit($pageSize)->asArray()->all();
        foreach($activityList as $k => $v){
            if(!empty($activityList[$k]['thumb'])){
                $activityList[$k]['thumb'] = SITE_URL.ltrim($activityList[$k]['thumb'], "./");
                $tmpArr = explode('uploads', $activityList[$k]['thumb']);
                $activityList[$k]['thumb'] = $tmpArr[0].'uploads'.Tools::getImgBySize($tmpArr[1]);//mini图 转化成 预览图
            };

            unset($activityList[$k]["cat_id"]);
            unset($activityList[$k]["copyfrom"]);
            unset($activityList[$k]["keywords"]);
            unset($activityList[$k]["activity_type"]);
            unset($activityList[$k]["status"]);
            unset($activityList[$k]["link"]);
            unset($activityList[$k]["readpoint"]);
            unset($activityList[$k]["sort"]);
            unset($activityList[$k]["last_modify_time"]);
            unset($activityList[$k]["add_time"]);
            unset($activityList[$k]["author"]);
            unset($activityList[$k]["content"]);

        }
        // P($activityList);
        return Tools::showRes(0, [
            'activityList' => $activityList,
            'pageInfo' => [
                'count' => $count,
                'currPage' => $currPage,
                'pageSize' => $pageSize,
                'totalPage' => ceil($count/$pageSize),
            ]
        ]);
    }


    /*详情*/
    public function actionActivityDetail()
    {
        $get = Yii::$app->request->get();
        $id = (int)(isset($get['id'])?$get['id']:0);
        if(!$id){
            return Tools::showRes(300, '参数有误！');
            Yii::$app->end();
        }
        $activity = Activity::find()->where('id = :id', [':id' => $id])->asArray()->one();
        $activity['content'] = Html::decode($activity['content']);

        if(!empty($activity['thumb'])){
            $activity['thumb'] = SITE_URL.ltrim($activity['thumb'], "./");
            $tmpArr = explode('uploads', $activity['thumb']);
            $activity['thumb'] = $tmpArr[0].'uploads'.Tools::getImgBySize($tmpArr[1], 'big');//mini图 转化成 预览图
            $activity['thumb'] = [$activity['thumb']];
        }

        if(!empty($activity['author'])){
            $activity['author'] = explode(" ", $activity['author']);
        };

        unset($activity["cat_id"]);
        unset($activity["copyfrom"]);
        unset($activity["keywords"]);
        unset($activity["activity_type"]);
        unset($activity["status"]);
        unset($activity["link"]);
        unset($activity["readpoint"]);
        unset($activity["sort"]);
        unset($activity["last_modify_time"]);
        unset($activity["add_time"]);
        unset($activity["author"]);

        // P($activity);
        return Tools::showRes(0, [
            'activity' => $activity
        ]);
    }

}
