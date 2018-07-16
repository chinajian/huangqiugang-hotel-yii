<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

class Room extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%room}}';
    }

    public function rules()
    {
        return [
            // ['room_name', 'required', 'message' => '客房名称不能为空'],
            ['room_name', 'string', 'max' => 128],
            ['room_type', 'required', 'message' => '客型不能为空'],
            ['room_type', 'string', 'max' => 32],
            ['acreage', 'string', 'max' => 16],
            ['floor', 'string', 'max' => 16],
            ['bed_type', 'string', 'max' => 16],
            ['preview', 'string', 'max' => 256],
            ['album_img', 'string', 'max' => 1024],
            ['desc', 'string', 'max' => 512],
        ];
    }


    /*添加客房*/
    public function addRoom($data)
    {
        // P($data);
        if($this->load($data) and $this->validate()){
            if($this->save(false)){
                /*写入日志*/
                SysLog::addLog('添加客房['. $data['Article']['title'] .']成功');
                return true;
            }
        }
        return false;
    }


    /*修改客房*/
    public function modRoom($id, $data)
    {
        if(isset($data['Room']['desc']) and !empty($data['Room']['desc'])){
            $data['Room']['desc'] = Html::encode($data['Room']['desc']);
        }
        
        if($this->load($data) and $this->validate()){
            $room = self::find()->where('room_id = :uid', [':uid' => $id])->one();
            if(is_null($room)){
               return false; 
            }
            $room->room_name = $data['Room']['room_name'];
            $room->room_type = $data['Room']['room_type'];
            $room->acreage = $data['Room']['acreage'];
            $room->floor = $data['Room']['floor'];
            $room->bed_type = $data['Room']['bed_type'];
            $room->preview = $data['Room']['preview']?$data['Room']['preview']:"";
            $room->album_img = $data['Room']['album_img']?$data['Room']['album_img']:"";
            $room->desc = $data['Room']['desc'];
            if($room->save(false)){
                /*写入日志*/
                SysLog::addLog('修改客房['. $room->room_name .']成功');
                return true;
            }
            return false;
        }
    }
    
}
