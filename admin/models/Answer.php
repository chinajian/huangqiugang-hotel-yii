<?php

namespace app\models;

use Yii;

class Answer extends \yii\db\ActiveRecord
{    
    public static function tableName()
    {
        return '{{%answer}}';
    }

    public function rules()
    {
        return [
            ['qid', 'required', 'message' => '问题ID不能为空'],
            ['qid', 'integer', 'message' => '问题ID格式不正确'],
            ['answer_content', 'required', 'message' => '答案不能为空'],
            ['answer_content', 'string', 'max' => 256],
            ['is_true', 'in', 'range' => ['1', '2'],  'message' => '是否正确格式不正确'],
        ];
    }

    /*添加答案*/
    public function addAnswer($data)
    {
        if($this->load($data) and $this->validate()){
            // P($data);
            if($this->save(false)){
                return true;
            }
        }
        return false;

    }


    /*
    修改答案
    $index 修改第几个价格 0代表第一个
    */
    public function modAnswer($qid, $data, $index)
    {
        // P($qid);
        if($this->load($data) and $this->validate()){
            /*根据索引，取出对应的价格区间*/
            $answer = self::find()->where('qid = :id', [':id' => $qid])->offset($index)->limit(1)->one();
            // P($answer);
            if(!empty($answer)){
                /*修改数据*/
                $answer->answer_content = $data['Answer']['answer_content'];
                $answer->is_true = $data['Answer']['is_true'];
                if($answer->save(false)){
                    return true;
                };
            }else{
                /*说明之前没有，需要添加新的数据*/
                if($this->addAnswer($data)){
                    return true;
                }
            }
            return false;
        }
    }
    
}
