<?php

namespace app\models;

use Yii;

class Room extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%room}}';
    }

    public function rules()
    {
        return [
            ['room_name', 'string', 'max' => 128],
            ['room_type', 'string', 'max' => 32],
            ['acreage', 'string', 'max' => 16],
            ['floor', 'string', 'max' => 16],
            ['bed_type', 'string', 'max' => 16],
            ['preview', 'string', 'max' => 256],
            ['desc', 'string', 'max' => 512],
        ];
    }


    /*
    根据 房型 取出对应的信息
    $room_type      房型
    */
    public function getRoomInfo($room_type)
    {
        $info = self::find()->select(['room_name', 'room_type', 'acreage', 'floor', 'bed_type', 'preview', 'album_img', 'desc'])->where('room_type = :room_type', [':room_type' => $room_type])->asArray()->one();
        return $info;
    }

    
}
