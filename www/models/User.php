<?php

namespace app\models;

use Yii;
use libs\WwwInfo;

class User extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function rules()
    {
        return [
            ['user_name', 'string', 'max' => 64],
            ['phone', 'required', 'message' => 'phone不能为空', 'on' => 'binding'],
            ['phone', 'string', 'max' => 16],
            ['password', 'required', 'message' => 'password不能为空', 'on' => 'binding'],
            ['password', 'string', 'max' => 64],
            ['wechat_openid', 'required', 'message' => 'wechat_openid不能为空', 'on' => 'add'],
            ['wechat_openid', 'string', 'max' => 64],
            ['wechat_nickname', 'string', 'max' => 512],
            ['wechat_sex', 'in', 'range' => ['0', '1', '2'], 'message' => '性别格式不正确'],
            ['wechat_headimgurl', 'string', 'max' => 512],
            ['wechat_country', 'string', 'max' => 16],
            ['wechat_province', 'string', 'max' => 16],
            ['wechat_city', 'string', 'max' => 16],
            [['reg_time', 'last_ip', 'regby', 'last_login_time', 'visit_count'], 'safe'],
        ];
    }



    /*
    添加会员
    $data   提交的数据
    */
    public function addUser($data)
    {
        // P($data);
        $this->scenario = 'add';
        if($this->load($data) and $this->validate()){
            if($this->save(false)){
                $user_id = $this->getPrimaryKey();
                WwwInfo::setLoginInfo($user_id, $data['User']['wechat_nickname']);//存入登录信息
                return true;
            }
        }
        return false;
    }

    /*
    修改会员
    $data       提交的数据
    $user_id    user_id
    $flag       场景
    */
    public function modUser($data, $user_id = 0, $flag = '')
    {
        // P($data);
        if($user_id != 0 && is_numeric($user_id)){
            if($flag == 'binding'){
                $this->scenario = 'binding';
            }
            if($this->load($data) and $this->validate()){
                $user = self::find()->where('user_id = :uid', [':uid' => $user_id])->one();
                if(empty($user)){//没有找到对应记录
                   return false; 
                };
                // P($user);
                if(isset($data['User']['user_name']) and !empty($data['User']['user_name'])){
                    $user->user_name = $data['User']['user_name'];
                }
                if(isset($data['User']['phone']) and !empty($data['User']['phone'])){
                    $user->phone = $data['User']['phone'];
                }
                if(isset($data['User']['password']) and !empty($data['User']['password'])){
                    $user->password = md5($data['User']['password']);
                }
                if($user->save(false)){
                    return true;
                };
            }
        }
        return false;
    }

    /*
    验证登录
    $phone  手机号
    $password   密码   
    */
    public function login($phone, $password)
    {
        $user = self::find()->where(['phone' => $phone, 'password' => $password])->one();
        // P($user);
        if(empty($user)){//没有找到此会员
            return false;
        }else{
            /*更新最后登录时间 和 登录次数*/
            $this->updateAll(['last_ip' => ip2long(Yii::$app->request->userIP), 'last_login_time' => time()], 'phone = :phone', [':phone' => $phone]);
            $this->updateAllCounters(['visit_count' => 1], 'phone = :phone', [':phone' => $phone]);
            WwwInfo::setLoginInfo($user['user_id'], $user['phone']);//存入登录信息
            return true;
        }
        return false;
    }
    
}
