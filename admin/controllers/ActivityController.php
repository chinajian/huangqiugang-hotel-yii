<?php

namespace admin\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use admin\controllers\BasicController;
use app\models\Activity;
use app\models\SysLog;
use yii\helpers\Html;


class ActivityController extends BasicController
{
    /**
     * 精彩活动列表
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
        // P($activityList);
        return $this->render('activityList', [
            'activityList' => $activityList,
            'pageInfo' => [
                'count' => $count,
                'currPage' => $currPage,
                'pageSize' => $pageSize,
            ]
        ]);
    }

    /**
     * 添加精彩活动
     */
    public function actionAddActivity()
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $activityModel = new Activity;
            if($activityModel->addActivity($post)){
                return showRes(200, '添加成功', Url::to(['activity/add-activity']));
                Yii::$app->end();
            }else{
                if($activityModel->hasErrors()){
                    return showRes(300, $activityModel->getErrors());
                }else{
                    return showRes(300, '添加失败');
                }
            }
            return;
        }
        return $this->render('addActivity');
    }

    /*
    修改精彩活动
    */
    public function actionModActivity()
    {
        /*如果有数据，进行修改*/
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $id = (int)(isset($post['Activity']['id'])?$post['Activity']['id']:0);
            if(!$id){
                return showRes(300, '参数有误！');
                Yii::$app->end();
            }
            $activityModel = new Activity;
            if($activityModel->modActivity($id, $post)){
                return showRes(200, '修改成功', 'back');
                Yii::$app->end();
            }else{
                if($activityModel->hasErrors()){
                    return showRes(300, $activityModel->getErrors());
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

        $activity = Activity::find()->where('id = :id', [':id' => $id])->asArray()->one();
        $activity['content'] = Html::decode($activity['content']);
        return $this->render('modActivity', [
            'activity' => $activity
        ]);

    }

    /*
	删除精彩活动
    */
    public function actionDelActivity()
    {
    	$post = Yii::$app->request->post();
        $id = (int)(isset($post['id'])?$post['id']:0);
        if(!$id){
            return showRes(300, '参数有误！');
            Yii::$app->end();
        }
        $activity = Activity::find()->where('id = :id', [':id' => $id])->one();
        if($activity and $activity->delete()){
            /*写入日志*/
            SysLog::addLog('删除精彩活动['. $activity->title .']成功');

            return showRes(200, '删除成功', Url::to(['activity/activity-list']));
            Yii::$app->end();
        }else{
            return showRes(300, '删除失败');
            Yii::$app->end();
        }
    }


}
