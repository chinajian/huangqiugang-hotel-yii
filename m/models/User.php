<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use libs\MInfo;

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
    $data   如果是已经关注的账号，可以返回头像等数据，如果没有关注，$data是错误信息
    $regby      3-通过微信注册
    */
    public function saveUser($data, $user_id = 0)
    {
        // P($data);
        if($this->load($data) and $this->validate()){
            if($user_id != 0 && is_numeric($user_id)){//编辑
                $user = self::find()->where('user_id = :uid', [':uid' => $user_id])->one();
                if(empty($user)){//没有找到对应记录
                   return false; 
                }
                $user->user_name = isset($data['user_name'])?$data['user_name']:'';
                if($user->save(false)){
                    return true;
                };
            }else{//新增
                $this->scenario = 'add';
                if($this->save(false)){
                    $user_id = $this->getPrimaryKey();
                    MInfo::setLoginInfo($user_id, $data['User']['wechat_nickname']);//存入登录信息
                    return true;
                }
            }
        }
        return false;
    }

    /*
    验证登录
    $data   如果是已经关注的账号，可以返回头像等数据，如果没有关注，$userinfo是错误信息
    */
    public function login($data)
    {
        $openid = $data['User']['wechat_openid'];
        $user = self::find()->where(['wechat_openid' => $openid])->one();
        // P($user);
        if(empty($user) && (isset($data['User']['regby']) && $data['User']['regby'] == 3)){//没有找到此会员，并且是微信过来的，需要增加一个会员
            /*新增会员*/
            if($this->saveUser($data)){
                return true;
            }
        }else{
            /*更新最后登录时间 和 登录次数*/
            $this->updateAll(['last_ip' => ip2long(Yii::$app->request->userIP), 'last_login_time' => time()], 'wechat_openid = :openid', [':openid' => $openid]);
            $this->updateAllCounters(['visit_count' => 1], 'wechat_openid = :openid', [':openid' => $openid]);
            MInfo::setLoginInfo($user['user_id'], $user['wechat_nickname']);//存入登录信息
            return true;
        }
        return false;
    }
    
}
