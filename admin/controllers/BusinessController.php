<?php

namespace admin\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use admin\controllers\BasicController;
use app\models\Business;
use app\models\SysLog;
use yii\helpers\Html;


class BusinessController extends BasicController
{
    /**
     * 商务中心列表
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
        // P($businessList);
        return $this->render('businessList', [
            'businessList' => $businessList,
            'pageInfo' => [
                'count' => $count,
                'currPage' => $currPage,
                'pageSize' => $pageSize,
            ]
        ]);
    }

    /**
     * 添加商务中心
     */
    public function actionAddBusiness()
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $businessModel = new Business;
            if($businessModel->addBusiness($post)){
                return showRes(200, '添加成功', Url::to(['business/add-business']));
                Yii::$app->end();
            }else{
                if($businessModel->hasErrors()){
                    return showRes(300, $businessModel->getErrors());
                }else{
                    return showRes(300, '添加失败');
                }
            }
            return;
        }
        return $this->render('addBusiness');
    }

    /*
    修改商务中心
    */
    public function actionModBusiness()
    {
        /*如果有数据，进行修改*/
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $id = (int)(isset($post['Business']['id'])?$post['Business']['id']:0);
            if(!$id){
                return showRes(300, '参数有误！');
                Yii::$app->end();
            }
            $businessModel = new Business;
            if($businessModel->modBusiness($id, $post)){
                return showRes(200, '修改成功', 'back');
                Yii::$app->end();
            }else{
                if($businessModel->hasErrors()){
                    return showRes(300, $businessModel->getErrors());
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

        $business = Business::find()->where('id = :id', [':id' => $id])->asArray()->one();
        $business['content'] = Html::decode($business['content']);
        return $this->render('modBusiness', [
            'business' => $business
        ]);

    }

    /*
	删除商务中心
    */
    public function actionDelBusiness()
    {
    	$post = Yii::$app->request->post();
        $id = (int)(isset($post['id'])?$post['id']:0);
        if(!$id){
            return showRes(300, '参数有误！');
            Yii::$app->end();
        }
        $business = Business::find()->where('id = :id', [':id' => $id])->one();
        if($business and $business->delete()){
            /*写入日志*/
            SysLog::addLog('删除商务中心['. $business->title .']成功');

            return showRes(200, '删除成功', Url::to(['business/business-list']));
            Yii::$app->end();
        }else{
            return showRes(300, '删除失败');
            Yii::$app->end();
        }
    }


}
