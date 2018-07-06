<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use libs\MInfo;

class Users extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%users}}';
    }

    public function rules()
    {
        return [
            ['user_name', 'string', 'max' => 64],
            ['password', 'string', 'max' => 64],
            ['wechat_openid', 'required', 'message' => 'openid不能为空', 'on' => 'add'],
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
    $userinfo   如果是已经关注的账号，可以返回头像等数据，如果没有关注，$userinfo是错误信息
    $regby  3-通过微信注册
    */
    public function addUser($openid, $userinfo, $regby = 3)
    {
        $this->scenario = 'add';
        // P($userinfo);
        $data['Users']['wechat_openid'] = $openid;
        $data['Users']['wechat_nickname'] = urlencode($userinfo['nickname']);
        $data['Users']['wechat_headimgurl'] = $userinfo['headimgurl'];
        $data['Users']['wechat_sex'] = $userinfo['sex'];
        $data['Users']['wechat_country'] = $userinfo['country'];
        $data['Users']['wechat_province'] = $userinfo['province'];
        $data['Users']['wechat_city'] = $userinfo['city'];
        $data['Users']['regby'] = $regby;
        $data['Users']['reg_time'] = time();
        if($this->load($data) and $this->validate()){
            if($this->save(false)){
                $user_id = $this->getPrimaryKey();
                MInfo::setLoginInfo($user_id, $data['Users']['wechat_nickname']);//存入登录信息
                return true;
            }
        }
        return false;
    }

    /*修改会员信息*/
    public function modUser($data)
    {
        $user_id = MInfo::getUserId();
        if($this->load($data) and $this->validate()){
            $user = self::find()->where('user_id = :uid', [':uid' => $user_id])->one();
            if(empty($user)){
               return false; 
            }

            if(isset($data['Users']['nickname']) and !empty($data['Users']['nickname'])){
                $user->nickname = $data['Users']['nickname'];
            }
            if(isset($data['Users']['sign']) and !empty($data['Users']['sign'])){
                $user->sign = $data['Users']['sign'];
            }
            if(isset($data['Users']['sex']) and !empty($data['Users']['sex'])){
                $user->sex = $data['Users']['sex'];
            }

            if($user->save(false)){
                return true;
            }

            return false;
        }
    }

    /*
    验证登录
    $userinfo   如果是已经关注的账号，可以返回头像等数据，如果没有关注，$userinfo是错误信息
    */
    public function login($openid, $userinfo)
    {
        $user = self::find()->where(['wechat_openid' => $openid])->one();
        // P($user);
        if(empty($user)){
            /*注册会员*/
            if($this->addUser($openid, $userinfo)){
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
