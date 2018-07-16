<?php

namespace admin\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use admin\controllers\BasicController;
use app\models\SysLog;


class SyslogController extends BasicController
{
    /**
     * 操作日志
     */
    public function actionIndex()
    {
    	$get = Yii::$app->request->get();
        if(isset($get['page'])){
            $currPage = (int)$get['page']?$get['page']:1;
        }else{
            $currPage = 1;
        }
        $sysLogModel = SysLog::find();
        $count = $sysLogModel->count();
        $pageSize = Yii::$app->params['pageSize'];
        $syslogList = $sysLogModel->offset($pageSize*($currPage-1))->limit($pageSize)->orderBy(['log_id'=>SORT_DESC])->all();
    	return $this->render('index', [
            'syslogList' => $syslogList,
    		'pageInfo' => [
                'count' => $count,
                'currPage' => $currPage,
                'pageSize' => $pageSize,
            ]
    	]);
    	return $this->render('index');
    }



}
