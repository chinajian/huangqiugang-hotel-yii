<?php

namespace app\models;

use Yii;
use libs\AdminInfo;

class SysConfig extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%sys_config}}';
    }

    public function rules()
    {
        return [
            ['sys_name', 'required', 'message' => '系统名称不能为空'],
            ['sys_name', 'string', 'max' => 64],
            ['sys_ename', 'string', 'max' => 64],
            ['albums', 'string', 'max' => 1024],
            ['hot_search', 'string', 'max' => 1024],
            ['introduce', 'required', 'message' => '简介不能为空'],
        ];
    }

    /*更新 系统配置*/
    public function set($data)
    {
        if(isset($data['SysConfig']['albums']) and !empty($data['SysConfig']['albums'])){
            $data['SysConfig']['albums'] = implode(',', $data['SysConfig']['albums']);
        }else{
            $data['SysConfig']['albums'] = '';
        }
        if($this->load($data) and $this->validate()){
            $sysConfig = self::find()->one();
            if(is_null($sysConfig)){
               return false; 
            }
            $sysConfig->sys_name = $data['SysConfig']['sys_name'];
            $sysConfig->sys_ename = $data['SysConfig']['sys_ename'];
            $sysConfig->albums = $data['SysConfig']['albums'];
            $sysConfig->hot_search = $data['SysConfig']['hot_search'];
            $sysConfig->introduce = $data['SysConfig']['introduce'];
            if($sysConfig->save(false)){
                /*写入日志*/
                SysLog::addLog('修改系统参数成功');
                return true;
            }
        }
        return false;
    }



}
