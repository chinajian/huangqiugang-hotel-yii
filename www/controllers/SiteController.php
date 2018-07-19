<?php
namespace www\controllers;

use Yii;
use yii\web\Controller;
use app\models\Article;
use app\models\ArticleCategory;
use app\models\Business;
use app\models\Activity;
use app\models\User;
use app\models\Msg;
use libs\Tools;
use libs\Page;
use yii\helpers\Html;


class SiteController extends Controller
{
    public $enableCsrfValidation = false;

    /*首页*/
    public function actionIndex()
    {
        /*周边推荐  B*/
        $articleModel = Article::find();
        /*游乐*/
        $articleList1 = $articleModel->with('articleCategory')->where("cat_id=1")->offset(0)->limit(2)->asArray()->all();
        foreach($articleList1 as $k => $v){
            if(!empty($articleList1[$k]['thumb'])){
                $articleList1[$k]['thumb'] = SITE_ADMIN_URL.ltrim($articleList1[$k]['thumb'], "./");
                $tmpArr = explode('uploads', $articleList1[$k]['thumb']);
                $articleList1[$k]['thumb'] = $tmpArr[0].'uploads'.Tools::getImgBySize($tmpArr[1], 'big');//mini图 转化成 预览图
            };
        }
        /*餐饮*/
        $articleList2 = $articleModel->with('articleCategory')->where("cat_id=2")->offset(0)->limit(2)->asArray()->all();
        foreach($articleList2 as $k => $v){
            if(!empty($articleList2[$k]['thumb'])){
                $articleList2[$k]['thumb'] = SITE_ADMIN_URL.ltrim($articleList2[$k]['thumb'], "./");
                $tmpArr = explode('uploads', $articleList2[$k]['thumb']);
                $articleList2[$k]['thumb'] = $tmpArr[0].'uploads'.Tools::getImgBySize($tmpArr[1], 'big');//mini图 转化成 预览图
            };
        }
        /*购物*/
        $articleList3 = $articleModel->with('articleCategory')->where("cat_id=3")->offset(0)->limit(2)->asArray()->all();
        foreach($articleList3 as $k => $v){
            if(!empty($articleList3[$k]['thumb'])){
                $articleList3[$k]['thumb'] = SITE_ADMIN_URL.ltrim($articleList3[$k]['thumb'], "./");
                $tmpArr = explode('uploads', $articleList3[$k]['thumb']);
                $articleList3[$k]['thumb'] = $tmpArr[0].'uploads'.Tools::getImgBySize($tmpArr[1], 'big');//mini图 转化成 预览图
            };
        }
        /*演绎*/
        $articleList4 = $articleModel->with('articleCategory')->where("cat_id=4")->offset(0)->limit(2)->asArray()->all();
        foreach($articleList4 as $k => $v){
            if(!empty($articleList4[$k]['thumb'])){
                $articleList4[$k]['thumb'] = SITE_ADMIN_URL.ltrim($articleList4[$k]['thumb'], "./");
                $tmpArr = explode('uploads', $articleList4[$k]['thumb']);
                $articleList4[$k]['thumb'] = $tmpArr[0].'uploads'.Tools::getImgBySize($tmpArr[1], 'big');//mini图 转化成 预览图
            };
        }
        // P($articleList4);


        /*周边推荐  E*/
        
        /*精彩活动 B*/
        $activityModel = Activity::find();
        $activityList = $activityModel->offset(0)->limit(5)->asArray()->all();
        foreach($activityList as $k => $v){
            if(!empty($activityList[$k]['thumb'])){
                $activityList[$k]['thumb'] = SITE_ADMIN_URL.ltrim($activityList[$k]['thumb'], "./");
                $tmpArr = explode('uploads', $activityList[$k]['thumb']);
                $activityList[$k]['thumb'] = $tmpArr[0].'uploads'.Tools::getImgBySize($tmpArr[1]);//mini图 转化成 预览图
            };
        }
        // P($activityList);
        /*精彩活动 E*/
        return $this->renderFile('./pc-view/dist/index.html.php', [
            'articleList1' => $articleList1,
            'articleList2' => $articleList2,
            'articleList3' => $articleList3,
            'articleList4' => $articleList4,
            'activityList' => $activityList,
        ]);
    }

    /*登录*/
    public function actionLogin()
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $phone = isset($post['account'])?$post['account']:"";
            $password = isset($post['password'])?md5($post['password']):"";
            $code = isset($post['code'])?$post['code']:"";
            
            if(empty($phone) or empty($password) or empty($code)){
                return Tools::showRes(300, "参数有误！");
            };

            // echo $phone.'-'.$code;
            /*验证 验证码*/
            $msg = Msg::find()->where('mobile = :phone and type = 2 and code = :code and is_use = 0', [':phone' => $phone, ':code' => $code])->one();
            if(empty($msg)){
                return Tools::showRes(10502, '无效的验证码');
                Yii::$app->end();
            }
            $time = time() - 5*60;//5分钟内有效
            if($time > $msg['send_time']){
                return Tools::showRes(10503, '此验证码已过期');
                Yii::$app->end();
            }

            $userModel = new User;
            if($userModel->login($phone, $password)){
                return Tools::showRes(200, '登录成功');
                Yii::$app->end();
            }else{
                if($userModel->hasErrors()){
                    return Tools::showRes(300, $userModel->getErrors());
                }
            }
            return Tools::showRes(300, '登录失败');
        }
        $this->layout = false;
        return $this->renderFile('./pc-view/dist/login.html.php');
    }

    /*品牌介绍*/
    public function actionIntro()
    {
        $actionName = $this->action->id;//方法名
        return $this->renderFile('./pc-view/dist/about_intro.html.php', [
            'actionName' => $actionName
        ]);
    }

    /*品牌故事*/
    public function actionStory()
    {
        $actionName = $this->action->id;//方法名
        return $this->renderFile('./pc-view/dist/about_story.html.php', [
            'actionName' => $actionName
        ]);
    }

    /*24小时*/
    public function actionHour()
    {
        $actionName = $this->action->id;//方法名
        return $this->renderFile('./pc-view/dist/24h.html.php', [
            'actionName' => $actionName
        ]);
    }

    /*通用设施*/
    public function actionCommon()
    {
        $actionName = $this->action->id;//方法名
        return $this->renderFile('./pc-view/dist/service_common.html.php', [
            'actionName' => $actionName
        ]);
    }

    /*商务中心*/
    public function actionBusiness()
    {
        $actionName = $this->action->id;//方法名
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
            'actionName' => $actionName,
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
        $actionName = $this->action->id;//方法名
        return $this->renderFile('./pc-view/dist/service_hotel.html.php', [
            'actionName' => $actionName
        ]);
    }

    /*服务项目*/
    public function actionProject()
    {
        $actionName = $this->action->id;//方法名
        return $this->renderFile('./pc-view/dist/service_project.html.php', [
            'actionName' => $actionName
        ]);
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
        $actionName = $this->action->id;//方法名
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
                $activityList[$k]['thumb'] = SITE_ADMIN_URL.ltrim($activityList[$k]['thumb'], "./");
                $tmpArr = explode('uploads', $activityList[$k]['thumb']);
                $activityList[$k]['thumb'] = $tmpArr[0].'uploads'.Tools::getImgBySize($tmpArr[1]);//mini图 转化成 预览图
            };
        }
        // P($activityList);
        $page = new Page($count, $pageSize);
        $pageInfo = $page->fpage(array(4, 5, 6));

        return $this->renderFile('./pc-view/dist/event.html.php', [
            'activityList' => $activityList,
            'pageInfo' => $pageInfo,
            'actionName' => $actionName,
            // 'pageInfo' => [
            //     'count' => $count,
            //     'currPage' => $currPage,
            //     'pageSize' => $pageSize,
            //     'totalPage' => ceil($count/$pageSize),
            // ]
        ]);
    }

    /*精彩活动 - 详情*/
    public function actionEventDetail()
    {
        $actionName = $this->action->id;//方法名
        $get = Yii::$app->request->get();
        $id = (int)(isset($get['id'])?$get['id']:0);
        if(!$id){
            return Tools::showRes(300, '参数有误！');
            Yii::$app->end();
        }
        $activity = Activity::find()->where('id = :id', [':id' => $id])->asArray()->one();
        $activity['content'] = Html::decode($activity['content']);

        if(!empty($activity['thumb'])){
            $activity['thumb'] = SITE_ADMIN_URL.ltrim($activity['thumb'], "./");
            $tmpArr = explode('uploads', $activity['thumb']);
            $activity['thumb'] = $tmpArr[0].'uploads'.Tools::getImgBySize($tmpArr[1], 'big');//mini图 转化成 预览图
            $activity['thumb'] = [$activity['thumb']];
        }

        if(!empty($activity['author'])){
            $activity['author'] = explode(" ", $activity['author']);
        };

        if(!empty($activity['content'])){
            $tmp = explode("./uploads", $activity['content']);
            if(count($tmp) > 1){
                $activity['content'] = $tmp[0];
                foreach($tmp as $k => $v){
                    if($k){
                        $activity['content'] .= SITE_ADMIN_URL."/uploads/".$v;
                    }
                }
            }
        };

        // P($activity);
        return $this->renderFile('./pc-view/dist/event_detail.html.php', [
            'activity' => $activity,
            'actionName' => $actionName
        ]);
    }


    /**
     * 周边推荐
     */
    public function actionRecommend()
    {
        $actionName = $this->action->id;//方法名
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
        $page = new Page($count, $pageSize);
        $pageInfo = $page->fpage(array(4, 5, 6));

        return $this->renderFile('./pc-view/dist/recommend.html.php', [
            'articleList' => $articleList,
            'active' => $catid,
            'pageInfo' => $pageInfo,
            'actionName' => $actionName,
            // 'pageInfo' => [
            //     'count' => $count,
            //     'currPage' => $currPage,
            //     'pageSize' => $pageSize,
            //     'totalPage' => ($pageSize>0)?ceil($count/$pageSize):0,
            // ]
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
            
            $tmp = explode("./uploads", $article['content']);
            if(count($tmp) > 1){
                $article['content'] = $tmp[0];
                foreach($tmp as $k => $v){
                    if($k){
                        $article['content'] .= SITE_ADMIN_URL."/uploads/".$v;
                    }
                }
            }
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
        $actionName = $this->action->id;//方法名
        return $this->renderFile('./pc-view/dist/contact.html.php', [
            'actionName' => $actionName
        ]);
    }

}
