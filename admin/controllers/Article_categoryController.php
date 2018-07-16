<?php

namespace admin\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use admin\controllers\BasicController;
use app\models\ArticleCategory;
use app\models\Article;
use app\models\SysLog;


class Article_categoryController extends BasicController
{
    /*
    推荐分类列表
    */
    public function actionCategoryList()
    {
        $categoryList = ArticleCategory::getTreeList();//获取所有分类
        return $this->render('categoryList', [
            'categoryList' => $categoryList
        ]);
    }

    /*
    添加推荐分类
    */
    public function actionAddCategory()
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $articleCategoryModel = new ArticleCategory;
            if($articleCategoryModel->addCategory($post)){
                return showRes(200, '添加成功', Url::to(['article_category/add-category']));
                Yii::$app->end();
            }else{
                if($articleCategoryModel->hasErrors()){
                    return showRes(300, $articleCategoryModel->getErrors());
                }else{
                    return showRes(300, '添加失败');
                }
            }
            return;
        }
        $categoryList = ArticleCategory::getTreeList();//获取所有分类
        return $this->render('addCategory',[
            'categoryList' => $categoryList
        ]);
        
    }

    /*
    修改推荐分类
    */
    public function actionModCategory()
    {
        /*如果有数据，进行修改*/
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $id = (int)(isset($post['ArticleCategory']['cat_id'])?$post['ArticleCategory']['cat_id']:0);
            if(!$id){
                return showRes(300, '参数有误！');
                Yii::$app->end();
            }
            $articleCategoryModel = new ArticleCategory;
            if($articleCategoryModel->modCategory($id, $post)){
                return showRes(200, '修改成功', Url::to(['article_category/category-list']));
                Yii::$app->end();
            }else{
                if($articleCategoryModel->hasErrors()){
                    return showRes(300, $articleCategoryModel->getErrors());
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

        $articleCategory = ArticleCategory::find()->where('cat_id = :id', [':id' => $id])->asArray()->one();
        return $this->render('modCategory', [
            'articleCategory' => $articleCategory,
            'categoryList' => $categoryList
        ]);
    }

    /*
    删除推荐分类
    */
    public function actionDelCategory()
    {
        $post = Yii::$app->request->post();
        $id = (int)(isset($post['id'])?$post['id']:0);
        if(!$id){
            return showRes(300, '参数有误！');
            Yii::$app->end();
        }

        $articleCategory = new ArticleCategory;

        if($articleCategory->delCategory($id)){
            return showRes(200, '删除成功', Url::to(['article_category/category-list']));
            Yii::$app->end();
        }else{
            if($articleCategory->hasErrors()){
                return showRes(300, $articleCategory->getErrors());
            }
            return showRes(300, '删除失败');
            Yii::$app->end();
        }
    }

}
