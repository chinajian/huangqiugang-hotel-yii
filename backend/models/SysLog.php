<?php

namespace app\models;

use Yii;
use libs\AdminInfo;


class SysLog extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%sys_log}}';
    }

    public function rules()
    {
        return [
            ['username', 'required', 'message' => '用户名不能为空'],
            // ['login_add', 'required', 'message' => '登录地址不能为空'],
            ['login_ip', 'required', 'message' => '登录IP不能为空'],
            ['content', 'required', 'message' => '日志内容不能为空'],
            ['content', 'string', 'max' => 64, 'message' => '日志内容最多64个字符'],
            ['operate_time', 'safe'],
        ];
    }

    /*添加系统日志*/
    public static function addLog($content='')
    {
        $syslogModel = new SysLog();
        $syslogModel->username = AdminInfo::getLoginName();
        $syslogModel->login_add = '';
        $syslogModel->login_ip = ip2long(Yii::$app->request->userIP);
        $syslogModel->content = $content;
        $syslogModel->operate_time = time();
        if($syslogModel->validate()){
            if($syslogModel->save(false)){
                return true;
            }
        }
        return false;
    }

}
