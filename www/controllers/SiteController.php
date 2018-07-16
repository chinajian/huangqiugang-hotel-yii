<?php
namespace www\controllers;

use Yii;
use yii\web\Controller;
use app\models\Article;
use app\models\ArticleCategory;
use libs\Tools;


class SiteController extends Controller
{
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
                'totalPage' => ($pageSize>0)?ceil($count/$pageSize):0,
            ]
        ]);
    }
}
