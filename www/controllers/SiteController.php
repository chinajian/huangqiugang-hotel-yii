<?php
namespace www\controllers;

use Yii;
use yii\web\Controller;
use app\models\Article;
use app\models\ArticleCategory;
use app\models\Business;
use libs\Tools;
use yii\helpers\Html;


class SiteController extends Controller
{
    /*首页*/
    public function actionIndex()
    {
        return $this->renderFile('./pc-view/dist/index.html.php');
    }

    /*登录*/
    public function actionLogin()
    {
        return $this->renderFile('./pc-view/dist/login.html.php');
    }

    /*品牌介绍*/
    public function actionIntro()
    {
        return $this->renderFile('./pc-view/dist/about_intro.html.php');
    }

    /*品牌故事*/
    public function actionStory()
    {
        return $this->renderFile('./pc-view/dist/about_story.html.php');
    }

    /*24小时*/
    public function actionHour()
    {
        return $this->renderFile('./pc-view/dist/24h.html.php');
    }

    /*通用设施*/
    public function actionCommon()
    {
        return $this->renderFile('./pc-view/dist/service_common.html.php');
    }

    /*商务中心*/
    public function actionBusiness()
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
                $businessList[$k]['thumb'] = SITE_ADMIN_URL.ltrim($businessList[$k]['thumb'], "./");
                $tmpArr = explode('uploads', $businessList[$k]['thumb']);
                $businessList[$k]['thumb'] = $tmpArr[0].'uploads'.Tools::getImgBySize($tmpArr[1]);//mini图 转化成 预览图
            };

        }
        // P($businessList);
        return $this->renderFile('./pc-view/dist/service_business.html.php', [
            'businessList' => $businessList,
            'pageInfo' => [
                'count' => $count,
                'currPage' => $currPage,
                'pageSize' => $pageSize,
                'totalPage' => ceil($count/$pageSize),
            ]
        ]);
    }

    /*酒店设施*/
    public function actionHotel()
    {
        return $this->renderFile('./pc-view/dist/service_hotel.html.php');
    }

    /*服务项目*/
    public function actionProject()
    {
        return $this->renderFile('./pc-view/dist/service_project.html.php');
    }

    /*在线预订*/
    public function actionOrder()
    {
        return $this->renderFile('./pc-view/dist/order.html.php');
    }

    /*在线预订 详情*/
    public function actionOrderDetail()
    {
        return $this->renderFile('./pc-view/dist/order_detail.html.php');
    }

    /*精彩活动*/
    public function actionEvent()
    {
        return $this->renderFile('./pc-view/dist/event.html.php');
    }

    /*精彩活动 - 详情*/
    public function actionEventDetail()
    {
        return $this->renderFile('./pc-view/dist/event_detail.html.php');
    }


    /**
     * 周边推荐
     */
    public function actionRecommend()
    {
        $get = Yii::$app->request->get();
        if(isset($get['page'])){
            $currPage = (int)$get['page']?$get['page']:1;
        }else{
            $currPage = 1;
        }
        $catWhere = '';
        if(isset($get['catid'])){
            $catid = $get['catid'];
            $catWhere = '{{%article}}.cat_id='.$catid;
        }else{
            $catid = 1;
            $catWhere = '{{%article}}.cat_id='.$catid;
        }
        // echo $catWhere;
        $articleModel = Article::find();
        $count = $articleModel->where($catWhere)->count();
        $pageSize = Yii::$app->params['pageSize'];
        $articleList = $articleModel->with('articleCategory')->where($catWhere)->offset($pageSize*($currPage-1))->limit($pageSize)->asArray()->all();
        foreach($articleList as $k => $v){
            if(!empty($articleList[$k]['thumb'])){
                $articleList[$k]['thumb'] = SITE_ADMIN_URL.ltrim($articleList[$k]['thumb'], "./");
                $tmpArr = explode('uploads', $articleList[$k]['thumb']);
                $articleList[$k]['thumb'] = $tmpArr[0].'uploads'.Tools::getImgBySize($tmpArr[1]);//mini图 转化成 预览图
            };

        }
        // P($articleList);
        return $this->renderFile('./pc-view/dist/recommend.html.php', [
            'articleList' => $articleList,
            'active' => $catid,
            'pageInfo' => [
                'count' => $count,
                'currPage' => $currPage,
                'pageSize' => $pageSize,
                'totalPage' => ($pageSize>0)?ceil($count/$pageSize):0,
            ]
        ]);
    }

    /*推荐详情*/
    public function actionRecommendDetail()
    {
        $get = Yii::$app->request->get();
        $id = (int)(isset($get['id'])?$get['id']:0);
        if(!$id){
            return $this->renderFile('./pc-view/dist/recommend_detail.html.php');
        }
        $article = Article::find()->where('article_id = :id', [':id' => $id])->asArray()->one();
        /*下一篇*/
        $nextArticle = Article::find()->where('article_id = :id', [':id' => ($id+1)])->asArray()->one();

        if(!empty($article['content'])){
            $article['content'] = Html::decode($article['content']);
        }
        if(!empty($nextArticle['content'])){
            $nextArticle['content'] = Html::decode($nextArticle['content']);
        }

        if(!empty($article['thumb'])){
            $article['thumb'] = SITE_ADMIN_URL.ltrim($article['thumb'], "./");
            $tmpArr = explode('uploads', $article['thumb']);
            $article['thumb'] = $tmpArr[0].'uploads'.Tools::getImgBySize($tmpArr[1], 'big');//mini图 转化成 预览图
            $article['thumb'] = [$article['thumb']];
        }
        if(!empty($nextArticle['thumb'])){
            $nextArticle['thumb'] = SITE_ADMIN_URL.ltrim($nextArticle['thumb'], "./");
            $tmpArr = explode('uploads', $nextArticle['thumb']);
            $nextArticle['thumb'] = $tmpArr[0].'uploads'.Tools::getImgBySize($tmpArr[1], 'big');//mini图 转化成 预览图
            $nextArticle['thumb'] = [$nextArticle['thumb']];
        }

        if(!empty($nextArticle['author'])){
            $nextArticle['author'] = explode(" ", $nextArticle['author']);
        };
        // P($article);
        // P($nextArticle);


        return $this->renderFile('./pc-view/dist/recommend_detail.html.php', [
            'article' => $article,
            'nextArticle' => $nextArticle
        ]);
    }

    /*联系我们*/
    public function actionContact()
    {
        return $this->renderFile('./pc-view/dist/contact.html.php');
    }

}
