<?php

namespace app\models;

use Yii;

class PayLog extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%pay_log}}';
    }

    public function rules()
    {
        return [
            ['user_id', 'required', 'message' => '会员没有选择'],
            ['user_id', 'integer', 'message' => '会员格式不正确'],
            ['order_sn', 'required', 'message' => '订单号不能为空'],
            // ['order_sn', 'string', 'max' => 50],
            ['money', 'required', 'message' => '交易金额不能为空'],
            ['money', 'number', 'message' => '交易金额格式不正确'],
            ['content', 'required', 'message' => '返回值不能为空'],
            [['pay_mode', 'add_time'], 'safe'],
        ];
    }

    /*添加日志
    $user_id 	会员id
    $info 	微信返回的所有信息
    */
    public function addLog($user_id, $info)
    {
        $data['PayLog']['user_id'] = $user_id;
        $data['PayLog']['order_sn'] = $info->out_trade_no;
        $data['PayLog']['money'] = $info->total_fee;
        $data['PayLog']['pay_mode'] = 3;
        $data['PayLog']['content'] = json_encode($info);
        $data['PayLog']['add_time'] = time();
        // P($data);
        if($this->load($data) and $this->validate()){
        // if($this->load($data)){
            if($this->save(false)){
                return true;
            }
        }
        return false;
    }


}
