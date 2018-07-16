<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

class Goods extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%goods}}';
    }

    public function rules()
    {
        return [
            // ['cat_ids', 'required', 'message' => '商品分类没有选择'],
            // ['cat_ids', 'required', 'message' => '分类格式不能为空'],
            ['cat_ids', 'string', 'max' => 500],
            ['goods_name', 'required', 'message' => '商品名称不能为空'],
            ['goods_name', 'string', 'max' => 120],
            ['goods_sn', 'required', 'message' => '商品货号没有数据'],
            ['goods_sn', 'string', 'max' => 60],

            ['keywords', 'string', 'max' => 255],
            ['goods_brief', 'string', 'max' => 255],
            ['goods_desc', 'required', 'message' => '商品描述不能为空'],
            ['album_img', 'string', 'max' => 1000],

            ['is_best', 'in', 'range' => [1, 2], 'message' => '精品格式不正确'],
            ['is_new', 'in', 'range' => [1, 2], 'message' => '新品格式不正确'],
            ['is_hot', 'in', 'range' => [1, 2], 'message' => '热销格式不正确'],
            ['integral', 'integer', 'message' => '赠送积分格式不正确'],
            
            ['is_sale', 'in', 'range' => [1, 2], 'message' => '开放销售格式不正确'],
            ['shelf_remarks', 'string', 'max' => 255],

            ['sort', 'integer', 'message' => '排序格式不正确'],

            [['shelf_time', 'last_update_time', 'add_time', 'is_recycle', 'is_delete'], 'safe'],
        ];
    }


    
}
