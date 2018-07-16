<?php

namespace app\models;

use Yii;

class Msg extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%msg}}';
    }

    public function rules()
    {
        return [
            ['mobile', 'required', 'message' => '手机不能为空'],
            ['mobile', 'string', 'max' => 16],
            ['code', 'required', 'message' => '验证码不能为空'],
            ['code', 'string', 'max' => 8],
            ['content', 'string', 'max' => 64],
            ['task_id', 'string', 'max' => 32],
            ['overage', 'integer', 'message' => '当前账户余额格式不正确'],
            ['mobile_count', 'integer', 'message' => '成功发送条数格式不正确'],
            ['status', 'string', 'max' => 16],
            ['desc', 'string', 'max' => 64],
            ['type', 'integer', 'message' => '类型格式不正确'],
            [['send_time'], 'safe'],
        ];
    }



    /*
    添加会员
    $data   提交的数据
    */
    public function addMsg($data)
    {
        if($this->load($data) and $this->validate()){
            if($this->save(false)){
                return true;
            }
        }
        return false;
    }

    
}
