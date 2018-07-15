<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

class Article extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%article}}';
    }

    public function rules()
    {
        return [
            ['cat_id', 'required', 'message' => '文章分类没有选择'],
            ['cat_id', 'integer', 'message' => '分类格式不正确'],
            ['title', 'required', 'message' => '文章名称不能为空'],
            ['title', 'string', 'max' => 150],
            ['thumb', 'string', 'max' => 100],
            ['content', 'required', 'message' => '文章内容不能为空'],
            ['author', 'string', 'max' => 30],
            ['copyfrom', 'string', 'max' => 100],
            ['keywords', 'string', 'max' => 255],
            ['article_type', 'integer', 'message' => '是否置顶格式不正确'],
            ['status', 'in', 'range' => [0, 1], 'message' => '状态格式不正确'],
            ['link', 'string', 'max' => 255],
            ['description', 'string', 'max' => 255],
            ['sort', 'integer', 'message' => '排序格式不正确'],
            [['readpoint', 'last_modify_time', 'add_time'], 'safe'],
        ];
    }



    /*添加文章*/
    public function addArticle($data)
    {
        if(isset($data['Article']['content']) and !empty($data['Article']['content'])){
            $data['Article']['content'] = Html::encode($data['Article']['content']);
        }
        if(!isset($data['Article']['sort']) or empty($data['Article']['sort'])){
            $data['Article']['sort'] = 0;
        }
        if(isset($data['Article']['add_time']) and !empty($data['Article']['add_time'])){
            $data['Article']['add_time'] = strtotime($data['Article']['add_time']);
        }else{
            $data['Article']['add_time'] = time();
        }
        // P($data);
        if($this->load($data) and $this->validate()){
            if($this->save(false)){
                /*写入日志*/
                SysLog::addLog('添加文章['. $data['Article']['title'] .']成功');
                return true;
            }
        }
        return false;
    }

    /*修改文章*/
    public function modArticle($id, $data)
    {
        if(isset($data['Article']['content']) and !empty($data['Article']['content'])){
            $data['Article']['content'] = Html::encode($data['Article']['content']);
        }
        
        if($this->load($data) and $this->validate()){
            $article = self::find()->where('article_id = :uid', [':uid' => $id])->one();
            if(is_null($article)){
               return false; 
            }
            $article->cat_id = $data['Article']['cat_id'];
            $article->title = $data['Article']['title'];
            $article->content = $data['Article']['content'];
            $article->author = $data['Article']['author'];
            $article->copyfrom = $data['Article']['copyfrom'];
            $article->keywords = $data['Article']['keywords'];
            $article->article_type = $data['Article']['article_type'];
            $article->status = $data['Article']['status'];
            $article->link = $data['Article']['link'];
            $article->description = $data['Article']['description'];
            $article->sort = $data['Article']['sort'];
            $article->last_modify_time = time();
            if($article->save(false)){
                /*写入日志*/
                SysLog::addLog('修改文章['. $article->title .']成功');
                return true;
            }
            return false;
        }
    }

    /*关联查询 分类信息*/
    public function getArticleCategory()
    {
        $category = $this->hasOne(ArticleCategory::className(), ['cat_id' => 'cat_id'])->select(['cat_id', 'cat_name']);
        return $category;
    }
    
}
