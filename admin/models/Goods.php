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



    /*添加商品*/
    public function addGoods($data)
    {
        // P($data);

        if(isset($data['Goods']['album_img']) and !empty($data['Goods']['album_img'])){
            $data['Goods']['album_img'] = implode(',', $data['Goods']['album_img']);
        }
        if(isset($data['Goods']['cat_ids']) and !empty($data['Goods']['cat_ids'])){
            $data['Goods']['cat_ids'] = implode(',', $data['Goods']['cat_ids']);
        }
        if(isset($data['Goods']['goods_sn']) and empty($data['Goods']['goods_sn'])){
            $data['Goods']['goods_sn'] = $this->createGoodsSn();
        }
        if(isset($data['Goods']['goods_desc']) and !empty($data['Goods']['goods_desc'])){
            $data['Goods']['goods_desc'] = Html::encode($data['Goods']['goods_desc']);
        }
        if(!isset($data['Goods']['integral']) or empty($data['Goods']['integral'])){
            $data['Goods']['integral'] = -1;
        }
        if(!isset($data['Goods']['sort']) or empty($data['Goods']['sort'])){
            unset($data['Goods']['sort']);
        }
        $data['Goods']['add_time'] = time();

        if($this->load($data) and $this->validate()){
            if($this->save(false)){
                /*写入日志*/
                SysLog::addLog('添加商品['. $data['Goods']['goods_name'] .']成功');
                return true;
            };
        }
        return false;
    }

    /*修改商品*/
    public function modGoods($id, $data)
    {
        // P($data);
        if(isset($data['Goods']['album_img']) and !empty($data['Goods']['album_img'])){
            $data['Goods']['album_img'] = implode(',', $data['Goods']['album_img']);
        }else{
            $data['Goods']['album_img'] = '';
        }
        if(isset($data['Goods']['cat_ids']) and !empty($data['Goods']['cat_ids'])){
            $data['Goods']['cat_ids'] = implode(',', $data['Goods']['cat_ids']);
        }else{
            $data['Goods']['cat_ids'] = "";
        }
        if(isset($data['Goods']['goods_sn']) and empty($data['Goods']['goods_sn'])){
            $data['Goods']['goods_sn'] = $this->createGoodsSn();
        }
        if(isset($data['Goods']['goods_desc']) and !empty($data['Goods']['goods_desc'])){
            $data['Goods']['goods_desc'] = Html::encode($data['Goods']['goods_desc']);
        }
        if(!isset($data['Goods']['integral']) or empty($data['Goods']['integral'])){
            $data['Goods']['integral'] = 0;
        }
        if(!isset($data['Goods']['price']) or empty($data['Goods']['price'])){
            $data['Goods']['price'] = 0;
        }
        if(!isset($data['Goods']['sort']) or empty($data['Goods']['sort'])){
            $data['Goods']['sort'] = 0;
        }
        // P($data);

        if($this->load($data) and $this->validate()){
            $goods = self::find()->where('goods_id = :id', [':id' => $id])->one();
            if(is_null($goods)){
               return false; 
            }
            // P($goods);

            $goods->goods_name = $data['Goods']['goods_name'];
            $goods->goods_sn = $data['Goods']['goods_sn'];
            $goods->integral = $data['Goods']['integral'];
            $goods->price = $data['Goods']['price'];
            $goods->album_img = $data['Goods']['album_img'];
            $goods->cat_ids = $data['Goods']['cat_ids'];
            $goods->is_new = $data['Goods']['is_new'];
            $goods->is_best = $data['Goods']['is_best'];
            $goods->is_hot = $data['Goods']['is_hot'];
            $goods->keywords = $data['Goods']['keywords'];
            $goods->goods_brief = $data['Goods']['goods_brief'];
            $goods->sort = $data['Goods']['sort'];
            $goods->goods_desc = $data['Goods']['goods_desc'];
            // P($goods);
            if($goods->save(false)){
                /*写入日志*/
                SysLog::addLog('修改商品['. $data['Goods']['goods_name'] .']成功');

                return true;
            };

        }
        return false;

    }


    /*
    产生唯一货号
    $len        长度
    */
    private function createGoodsSn($len = 8){
        $prefix = "ghc_";
        $goods_sn = str_pad(date('Ym', time()).mt_rand(100, 999).date('d', time()), $len, '0', STR_PAD_LEFT);//补零
        $goods = self::find()->where('goods_sn = :sn', [':sn' => $goods_sn])->one();
        return $goods?$this->createGoodsSn():$prefix.$goods_sn;  //如果商品货号重复则重新生成  

    }

    
}
