<?php

namespace admin\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use admin\controllers\BasicController;
use app\models\ArticleCategory;
use app\models\Article;
use app\models\SysLog;
use yii\helpers\Html;


class ArticleController extends BasicController
{
    /**
     * 推荐列表
     */
    public function actionArticleList()
    {
        $get = Yii::$app->request->get();
        if(isset($get['page'])){
            $currPage = (int)$get['page']?$get['page']:1;
        }else{
            $currPage = 1;
        }
        $articleModel = Article::find();
        $count = $articleModel->count();
        $pageSize = Yii::$app->params['pageSize'];
        $articleList = $articleModel->with('articleCategory')->offset($pageSize*($currPage-1))->limit($pageSize)->asArray()->all();
        return $this->render('articleList', [
            'articleList' => $articleList,
            'pageInfo' => [
                'count' => $count,
                'currPage' => $currPage,
                'pageSize' => $pageSize,
            ]
        ]);
    }

    /**
     * 添加推荐
     */
    public function actionAddArticle()
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $articleModel = new Article;
            if($articleModel->addArticle($post)){
                return showRes(200, '添加成功', Url::to(['article/add-article']));
                Yii::$app->end();
            }else{
                if($articleModel->hasErrors()){
                    return showRes(300, $articleModel->getErrors());
                }else{
                    return showRes(300, '添加失败');
                }
            }
            return;
        }
        $categoryList = ArticleCategory::getTreeList();//获取所有分类
        return $this->render('addArticle',[
            'categoryList' => $categoryList
        ]);
    }

    /*
    修改推荐
    */
    public function actionModArticle()
    {
        /*如果有数据，进行修改*/
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $id = (int)(isset($post['Article']['article_id'])?$post['Article']['article_id']:0);
            if(!$id){
                return showRes(300, '参数有误！');
                Yii::$app->end();
            }
            $articleModel = new Article;
            if($articleModel->modArticle($id, $post)){
                return showRes(200, '修改成功', 'back');
                Yii::$app->end();
            }else{
                if($articleModel->hasErrors()){
                    return showRes(300, $articleModel->getErrors());
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
        $categoryList = ArticleCategory::getTreeList();//获取所有分类

        $article = Article::find()->where('article_id = :id', [':id' => $id])->asArray()->one();
        $article['content'] = Html::decode($article['content']);
        return $this->render('modArticle', [
            'article' => $article,
            'categoryList' => $categoryList
        ]);

    }

    /*
	删除推荐
    */
    public function actionDelArticle()
    {
    	$post = Yii::$app->request->post();
        $id = (int)(isset($post['id'])?$post['id']:0);
        if(!$id){
            return showRes(300, '参数有误！');
            Yii::$app->end();
        }
        $article = Article::find()->where('article_id = :id', [':id' => $id])->one();
        if($article and $article->delete()){
            /*写入日志*/
            SysLog::addLog('删除推荐['. $article->title .']成功');

            return showRes(200, '删除成功', Url::to(['article/article-list']));
            Yii::$app->end();
        }else{
            return showRes(300, '删除失败');
            Yii::$app->end();
        }
    }


}
