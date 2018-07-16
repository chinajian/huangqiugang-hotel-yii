<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

class Activity extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%activity}}';
    }

    public function rules()
    {
        return [
            // ['cat_id', 'required', 'message' => '活动分类没有选择'],
            // ['cat_id', 'integer', 'message' => '分类格式不正确'],
            ['title', 'required', 'message' => '活动名称不能为空'],
            ['title', 'string', 'max' => 150],
            ['thumb', 'string', 'max' => 100],
            ['content', 'required', 'message' => '活动内容不能为空'],
            ['author', 'string', 'max' => 255],
            ['performance_time', 'string', 'max' => 100],
            ['keywords', 'string', 'max' => 255],
            ['activity_type', 'integer', 'message' => '是否置顶格式不正确'],
            ['status', 'in', 'range' => [0, 1], 'message' => '状态格式不正确'],
            ['link', 'string', 'max' => 255],
            ['description', 'string', 'max' => 255],
            ['sort', 'integer', 'message' => '排序格式不正确'],
            [['readpoint', 'last_modify_time', 'add_time'], 'safe'],
        ];
    }



    /*添加活动*/
    public function addActivity($data)
    {
        if(isset($data['Activity']['content']) and !empty($data['Activity']['content'])){
            $data['Activity']['content'] = Html::encode($data['Activity']['content']);
        }
        if(!isset($data['Activity']['sort']) or empty($data['Activity']['sort'])){
            $data['Activity']['sort'] = 0;
        }
        if(isset($data['Activity']['add_time']) and !empty($data['Activity']['add_time'])){
            $data['Activity']['add_time'] = strtotime($data['Activity']['add_time']);
        }else{
            $data['Activity']['add_time'] = time();
        }
        // P($data);
        if($this->load($data) and $this->validate()){
            if($this->save(false)){
                /*写入日志*/
                SysLog::addLog('添加活动['. $data['Activity']['title'] .']成功');
                return true;
            }
        }
        return false;
    }

    /*修改活动*/
    public function modActivity($id, $data)
    {
        if(isset($data['Activity']['content']) and !empty($data['Activity']['content'])){
            $data['Activity']['content'] = Html::encode($data['Activity']['content']);
        }
        
        if($this->load($data) and $this->validate()){
            $activity = self::find()->where('id = :uid', [':uid' => $id])->one();
            if(is_null($activity)){
               return false; 
            }
            // $activity->cat_id = $data['Activity']['cat_id'];
            $activity->title = $data['Activity']['title'];
            $activity->thumb = $data['Activity']['thumb'];
            $activity->content = $data['Activity']['content'];
            $activity->author = $data['Activity']['author']?$data['Activity']['author']:'';
            $activity->performance_time = $data['Activity']['performance_time'];
            $activity->keywords = $data['Activity']['keywords'];
            $activity->activity_type = $data['Activity']['activity_type'];
            $activity->status = $data['Activity']['status'];
            $activity->link = $data['Activity']['link'];
            $activity->description = $data['Activity']['description'];
            $activity->sort = $data['Activity']['sort'];
            $activity->last_modify_time = time();
            if($activity->save(false)){
                /*写入日志*/
                SysLog::addLog('修改活动['. $activity->title .']成功');
                return true;
            }
            return false;
        }
    }
    
}
