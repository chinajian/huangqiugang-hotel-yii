<?php

namespace app\models;

use Yii;
use libs\MInfo;

class OrderInfo extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%order_info}}';
    }

    public function rules()
    {
        return [
            ['user_id', 'integer', 'message' => '会员ID格式不正确'],
            ['rsv_man', 'string', 'max' => 32],
            ['mobile', 'string', 'max' => 32],
            ['rm_num', 'integer', 'message' => '房数格式不正确'],
            ['adult', 'integer', 'message' => '人数格式不正确'],
            ['order_sn', 'string', 'max' => 64],
            ['order_status', 'in', 'range' => ['1', '2', '3', '4', '5'], 'message' => '订单状态格式不正确'],
            ['rateCode', 'string', 'max' => 32],
            ['rmtype', 'string', 'max' => 32],
            ['crs_no', 'string', 'max' => 32],
            ['pay_id', 'integer', 'message' => '支付ID格式不正确'],
            ['pay_name', 'string', 'max' => 8],
            ['price', 'number', 'message' => '价格式不正确'],
            ['money_paid', 'number', 'message' => '已付价格式不正确'],
            ['remarks', 'string', 'max' => 512],
            ['arr', 'integer', 'message' => '到达时间格式不正确'],
            ['dep', 'integer', 'message' => '离开时间格式不正确'],
            [['pay_time', 'add_time'], 'safe'],
        ];
    }

    /*
    添加订单
    $data   提交的数据
    */
    public function addOrder($data)
    {
        // P($data);
        if(!isset($data['OrderInfo']['crs_no']) or empty($data['OrderInfo']['crs_no'])){
            $data['OrderInfo']['crs_no'] = "";
        }
        $data['OrderInfo']['add_time'] = time();
        $data['OrderInfo']['order_sn'] = $this->createOrderSn();
        $data['OrderInfo']['order_status'] = 1;
        if($this->load($data) and $this->validate()){
            // P($data);
            if($this->save(false)){
                $order_id = $this->getPrimaryKey();
                return $order_id;
            }
        }
        return false;
    }

    /*
    产生唯一订单号
    $len        长度
    */
    private function createOrderSn($len = 8){
        $order_sn = str_pad(date('Ym', time()).mt_rand(1000, 9999).date('d', time()), $len, '0', STR_PAD_LEFT);//补零
        $orderInfo = self::find()->where('order_sn = :sn', [':sn' => $order_sn])->one();
        return $orderInfo?$this->createOrderSn():$order_sn;  //如果商品货号重复则重新生成  
    }
    
}
