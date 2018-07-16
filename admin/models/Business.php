<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

class Business extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%business}}';
    }

    public function rules()
    {
        return [
            // ['cat_id', 'required', 'message' => '商务分类没有选择'],
            // ['cat_id', 'integer', 'message' => '分类格式不正确'],
            ['title', 'required', 'message' => '商务名称不能为空'],
            ['title', 'string', 'max' => 150],
            ['thumb', 'string', 'max' => 100],
            ['content', 'required', 'message' => '商务内容不能为空'],
            ['author', 'string', 'max' => 255],
            ['area', 'string', 'max' => 255],
            ['capacity', 'string', 'max' => 255],
            ['shape', 'string', 'max' => 255],
            ['keywords', 'string', 'max' => 255],
            ['business_type', 'integer', 'message' => '是否置顶格式不正确'],
            ['status', 'in', 'range' => [0, 1], 'message' => '状态格式不正确'],
            ['link', 'string', 'max' => 255],
            ['description', 'string', 'max' => 255],
            ['sort', 'integer', 'message' => '排序格式不正确'],
            [['readpoint', 'last_modify_time', 'add_time'], 'safe'],
        ];
    }



    /*添加商务*/
    public function addBusiness($data)
    {
        if(isset($data['Business']['content']) and !empty($data['Business']['content'])){
            $data['Business']['content'] = Html::encode($data['Business']['content']);
        }
        if(!isset($data['Business']['sort']) or empty($data['Business']['sort'])){
            $data['Business']['sort'] = 0;
        }
        if(isset($data['Business']['add_time']) and !empty($data['Business']['add_time'])){
            $data['Business']['add_time'] = strtotime($data['Business']['add_time']);
        }else{
            $data['Business']['add_time'] = time();
        }
        // P($data);
        if($this->load($data) and $this->validate()){
            if($this->save(false)){
                /*写入日志*/
                SysLog::addLog('添加商务['. $data['Business']['title'] .']成功');
                return true;
            }
        }
        return false;
    }

    /*修改商务*/
    public function modBusiness($id, $data)
    {
        if(isset($data['Business']['content']) and !empty($data['Business']['content'])){
            $data['Business']['content'] = Html::encode($data['Business']['content']);
        }
        
        if($this->load($data) and $this->validate()){
            $business = self::find()->where('id = :uid', [':uid' => $id])->one();
            if(is_null($business)){
               return false; 
            }
            // $business->cat_id = $data['Business']['cat_id'];
            $business->title = $data['Business']['title'];
            $business->thumb = $data['Business']['thumb'];
            $business->content = $data['Business']['content'];
            $business->author = $data['Business']['author']?$data['Business']['author']:'';
            $business->area = $data['Business']['area'];
            $business->capacity = $data['Business']['capacity'];
            $business->shape = $data['Business']['shape'];
            $business->keywords = $data['Business']['keywords'];
            $business->business_type = $data['Business']['business_type'];
            $business->status = $data['Business']['status'];
            $business->link = $data['Business']['link'];
            $business->description = $data['Business']['description'];
            $business->sort = $data['Business']['sort'];
            $business->last_modify_time = time();
            if($business->save(false)){
                /*写入日志*/
                SysLog::addLog('修改商务['. $business->title .']成功');
                return true;
            }
            return false;
        }
    }
    
}
