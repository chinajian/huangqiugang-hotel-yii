<?php

namespace app\models;

use Yii;
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
    $data   提交的数据
    */
    public function addUser($data)
    {
        // P($data);
        $newData['User']['phone'] = $data["User"]["phone"];
        $newData['User']['password'] = md5($data["User"]["password"]);
        $newData['User']['wechat_openid'] = MInfo::getWechatInfo()["wechat_openid"];
        $newData['User']['wechat_nickname'] = MInfo::getWechatInfo()["wechat_nickname"];
        $newData['User']['wechat_headimgurl'] = MInfo::getWechatInfo()["wechat_headimgurl"];
        $newData['User']['wechat_sex'] = MInfo::getWechatInfo()["wechat_sex"];
        $newData['User']['wechat_country'] = MInfo::getWechatInfo()["wechat_country"];
        $newData['User']['wechat_province'] = MInfo::getWechatInfo()["wechat_province"];
        $newData['User']['wechat_city'] = MInfo::getWechatInfo()["wechat_city"];
        $newData['User']['regby'] = 3;//3-通过微信注册
        $newData['User']['reg_time'] = time();
        $this->scenario = 'add';
        if($this->load($newData) and $this->validate()){
            if($this->save(false)){
                $user_id = $this->getPrimaryKey();
                MInfo::setLoginInfo($user_id, $newData['User']);//存入登录信息
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
    $data   如果是已经关注的账号，可以返回头像等数据，如果没有关注，$userinfo是错误信息
    */
    public function login($data)
    {
        $openid = $data['User']['wechat_openid'];
        $user = self::find()->where(['wechat_openid' => $openid])->one();
        // P($user);
        if(empty($user)){//没有找到此会员，先存入session，等到绑定手机的时候，一起添加到数据库
            MInfo::setLoginInfo("", $data['User']);//存入登录信息
            return true;
            /*新增会员*/
            // if($this->addUser($data)){
            //     return true;
            // }
        }else{
            /*更新最后登录时间 和 登录次数*/
            $this->updateAll(['last_ip' => ip2long(Yii::$app->request->userIP), 'last_login_time' => time()], 'wechat_openid = :openid', [':openid' => $openid]);
            $this->updateAllCounters(['visit_count' => 1], 'wechat_openid = :openid', [':openid' => $openid]);

            $wechatInfo['phone'] = $user->phone;
            $wechatInfo['wechat_openid'] = $user->wechat_openid;
            $wechatInfo['wechat_nickname'] = $user->wechat_nickname;
            $wechatInfo['wechat_headimgurl'] = $user->wechat_headimgurl;
            $wechatInfo['wechat_sex'] = $user->wechat_sex;
            $wechatInfo['wechat_country'] = $user->wechat_country;
            $wechatInfo['wechat_province'] = $user->wechat_province;
            $wechatInfo['wechat_city'] = $user->wechat_city;
            MInfo::setLoginInfo($user->user_id, $wechatInfo);//存入登录信息
            return true;
        }
        return false;
    }
    
}
