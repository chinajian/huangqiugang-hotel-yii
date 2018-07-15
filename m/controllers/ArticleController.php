<?php

namespace m\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use app\models\Article;
use app\models\ArticleCategory;
use yii\helpers\Html;
use libs\Tools;


class ArticleController extends Controller
{
    public function beforeAction($action)
    {
        header('Access-Control-Allow-Credentials:true');
        header('Access-Control-Allow-Origin:http://m.ghchotel.com');
        // header('Access-Control-Allow-Origin:http://10.9.87.104:3000');
        header('Access-Control-Allow-Methods:POST,GET');
        return true;
    }
    
    /*推荐分类*/
    public function actionCategoryList()
    {
        $categoryList = ArticleCategory::getTreeList();//获取所有分类
        foreach($categoryList as $k => $v){
            unset($categoryList[$k]["cat_type"]);
            unset($categoryList[$k]["parent_id"]);
            unset($categoryList[$k]["all_parent_id"]);
            unset($categoryList[$k]["child"]);
            unset($categoryList[$k]["all_child_id"]);
            unset($categoryList[$k]["link"]);
            unset($categoryList[$k]["article_number"]);
            unset($categoryList[$k]["hits"]);
            unset($categoryList[$k]["keywords"]);
            unset($categoryList[$k]["sort"]);
            unset($categoryList[$k]["show_in_nav"]);
            unset($categoryList[$k]["level"]);
            unset($categoryList[$k]["cat_desc"]);
        }
        // P($categoryList);
        return Tools::showRes(0, [
            'categoryList' => $categoryList
        ]);
    }



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
        $catWhere = '';
        if(isset($get['catid'])){
            $catid = $get['catid']?$get['catid']:'';
            if($catid){
                $catWhere = '{{%article}}.cat_id='.$get['catid'];
            }
        }
        $articleModel = Article::find();
        $count = $articleModel->where($catWhere)->count();
        $pageSize = Yii::$app->params['pageSize'];
        $articleList = $articleModel->with('articleCategory')->where($catWhere)->offset($pageSize*($currPage-1))->limit($pageSize)->asArray()->all();
        foreach($articleList as $k => $v){
            if(!empty($articleList[$k]['thumb'])){
                $articleList[$k]['thumb'] = SITE_URL.ltrim($articleList[$k]['thumb'], "./");
                $tmpArr = explode('uploads', $articleList[$k]['thumb']);
                $articleList[$k]['thumb'] = $tmpArr[0].'uploads'.Tools::getImgBySize($tmpArr[1]);//mini图 转化成 预览图
            };

            unset($articleList[$k]["cat_id"]);
            unset($articleList[$k]["copyfrom"]);
            unset($articleList[$k]["keywords"]);
            unset($articleList[$k]["article_type"]);
            unset($articleList[$k]["status"]);
            unset($articleList[$k]["link"]);
            unset($articleList[$k]["readpoint"]);
            unset($articleList[$k]["sort"]);
            unset($articleList[$k]["last_modify_time"]);
            unset($articleList[$k]["add_time"]);
            unset($articleList[$k]["author"]);
            unset($articleList[$k]["content"]);

        }
        // P($articleList);
        return Tools::showRes(0, [
            'articleList' => $articleList,
            'pageInfo' => [
                'count' => $count,
                'currPage' => $currPage,
                'pageSize' => $pageSize,
                'totalPage' => ceil($count/$pageSize),
            ]
        ]);
    }


    /*详情*/
    public function actionArticleDetail()
    {
        $get = Yii::$app->request->get();
        $id = (int)(isset($get['id'])?$get['id']:0);
        if(!$id){
            return showRes(300, '参数有误！');
            Yii::$app->end();
        }
        $article = Article::find()->where('article_id = :id', [':id' => $id])->asArray()->one();
        $article['content'] = Html::decode($article['content']);

        if(!empty($article['thumb'])){
            $article['thumb'] = SITE_URL.ltrim($article['thumb'], "./");
            $tmpArr = explode('uploads', $article['thumb']);
            $article['thumb'] = $tmpArr[0].'uploads'.Tools::getImgBySize($tmpArr[1], 'big');//mini图 转化成 预览图
            $article['thumb'] = [$article['thumb']];
        }

        if(!empty($article['author'])){
            $article['author'] = explode(" ", $article['author']);
        };

        unset($article["cat_id"]);
        unset($article["copyfrom"]);
        unset($article["keywords"]);
        unset($article["article_type"]);
        unset($article["status"]);
        unset($article["link"]);
        unset($article["readpoint"]);
        unset($article["sort"]);
        unset($article["last_modify_time"]);
        unset($article["add_time"]);

        // P($article);
        return Tools::showRes(0, [
            'article' => $article
        ]);
    }

}
