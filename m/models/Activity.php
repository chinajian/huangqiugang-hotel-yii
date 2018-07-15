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

    
}
