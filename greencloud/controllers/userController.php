<?php
namespace greencloud\controllers;

use Yii;
use greencloud\controllers\BasicController;
use \Curl\Curl;
use libs\Tools;
use libs\GreencloudInfo;

class UserController extends BasicController
{
    /*测试连接*/
	public function actionHello()
    {
    	$param = array(
    		'appKey' => $this->appKey,
		    'sessionId' => GreencloudInfo::getSessionid(),
		    'hotelGroupId' => $this->hotelGroupId,
    	);
    	$param['sign'] = $this->computeSign($param);

    	$curl = new Curl();
		$curl->post(Yii::$app->params['api']['user'].'hello', $param);
		if ($curl->error) {
		    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
		} else {
		    Tools::p($curl->response);
		}
    }

    /*2.会员配置相关接口方法*/
    //2.2.获取所有会员计划
    public function actionGetAllCardType()
    {
        $post = array(
            'isPhysical' => 'T',
        );
        $param = array(
            'appKey' => $this->appKey,
            'sessionId' => GreencloudInfo::getSessionid(),
            'hotelGroupId' => $this->hotelGroupId,
            'hotelId' => $this->hotelId,
            'isPhysical' => $post['isPhysical'],
        );
        $param['sign'] = $this->computeSign($param);

        $curl = new Curl();
        $curl->post(Yii::$app->params['api']['user'].'getAllCardType', $param);
        if ($curl->error) {
            echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            Tools::p($curl->response);
        }
    }

    //2.3.获取所有会员卡等级
    public function actionGetAllCardLevel()
    {
        $post = array(
            'cardType' => '',
            'isPhysical' => 'T',
        );
        $param = array(
            'appKey' => $this->appKey,
            'sessionId' => GreencloudInfo::getSessionid(),
            'hotelGroupId' => $this->hotelGroupId,
            'hotelId' => $this->hotelId,
            'cardType' => $post['cardType'],
            'isPhysical' => $post['isPhysical'],
        );
        $param['sign'] = $this->computeSign($param);

        $curl = new Curl();
        $curl->post(Yii::$app->params['api']['user'].'getAllCardLevel', $param);
        if ($curl->error) {
            echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            Tools::p($curl->response);
        }
    }

    //2.4.积分兑换物品列表
    public function actionGetExchangItemList()
    {
        $post = array(

        );
        $param = array(
            'appKey' => $this->appKey,
            'sessionId' => GreencloudInfo::getSessionid(),
            'hotelGroupId' => $this->hotelGroupId,
            'hotelId' => $this->hotelId,
        );
        $param['sign'] = $this->computeSign($param);

        $curl = new Curl();
        $curl->post(Yii::$app->params['api']['user'].'getExchangItemList', $param);
        if ($curl->error) {
            echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            Tools::p($curl->response);
        }
    }

    //2.5.获取物品类别列表
    public function actionGetGoodsCategoryList()
    {
        $post = array(

        );
        $param = array(
            'appKey' => $this->appKey,
            'sessionId' => GreencloudInfo::getSessionid(),
            'hotelGroupId' => $this->hotelGroupId,
        );
        $param['sign'] = $this->computeSign($param);

        $curl = new Curl();
        $curl->post(Yii::$app->params['api']['user'].'getGoodsCategoryList', $param);
        if ($curl->error) {
            echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            Tools::p($curl->response);
        }
    }

    /*3.注册相关接口方法*/
    // 3.1. 验证性会员注册第一步
    public function actionRegisterMemberCardApply()
    {
        $post = array(
            'name' => 'weiwei',
            'sex' => '2',
            'idType' => '',
            'idNo' => '',
            'mobile' => '13915028703',
            'email' => '',
            'verifyType' => 0,
            'verifyHost' => '',
            'cardType' => '',
        );
        $param = array(
            'appKey' => $this->appKey,
            'sessionId' => GreencloudInfo::getSessionid(),
            'hotelGroupId' => $this->hotelGroupId,
            'hotelId' => $this->hotelId,
            'name' => $post['name'],
            'sex' => $post['sex'],
            'idType' => $post['idType'],
            'idNo' => $post['idNo'],
            'mobile' => $post['mobile'],
            'email' => $post['email'],
            'verifyType' => $post['verifyType'],
            'verifyHost' => $post['verifyHost'],
            'cardType' => $post['cardType'],
        );
        $param['sign'] = $this->computeSign($param);

        $curl = new Curl();
        $curl->post(Yii::$app->params['api']['user'].'registerMemberCardApply', $param);
        if ($curl->error) {
            echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            Tools::p($curl->response);
        }
    }

    // 3.2. 验证性会员注册第二步
    public function actionRegisterMemberCard()
    {
        $post = array(
            'applyId' => 65,//注册返回的id(3.1. 验证性会员注册第一步中有返回)
            'mobileOrEmail' => '13915028703',//手机号或邮箱
            'verifyCode' => '',//验证码
            'openIdType' => '',//第三方类型
            'openIdUserId' => '',//第三方登陆唯一标识
            'cardType' => '',//会员计划
            'cardLevel' => '',//卡等级
            'cardSrc' => '',//来源
            'cardSales' => '',//卡销售员
            'referrerId' => '',//
            'companyId' => '',//
            'passwordC' => '',//
        );
        $param = array(
            'appKey' => $this->appKey,
            'sessionId' => GreencloudInfo::getSessionid(),
            'hotelGroupId' => $this->hotelGroupId,
            'hotelId' => $this->hotelId,
            'applyId' => $post['applyId'],//注册返回的id(3.1. 验证性会员注册第一步)
            'mobileOrEmail' => $post['mobileOrEmail'],//手机号或邮箱
            'verifyCode' => $post['verifyCode'],//验证码
            'openIdType' => $post['openIdType'],//第三方类型
            'openIdUserId' => $post['openIdUserId'],//第三方登陆唯一标识
            'cardType' => $post['cardType'],//会员计划
            'cardLevel' => $post['cardLevel'],//卡等级
            'cardSrc' => $post['cardSrc'],//来源
            'cardSales' => $post['cardSales'],//卡销售员
            'referrerId' => $post['referrerId'],
            'companyId' => $post['companyId'],
            'passwordC' => $post['passwordC'],
        );
        $param['sign'] = $this->computeSign($param);

        $curl = new Curl();
        $curl->post(Yii::$app->params['api']['user'].'registerMemberCard', $param);
        if ($curl->error) {
            echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            Tools::p($curl->response);
        }
    }

    // 3.3. 非验证性会员注册
    // 3.4. 会员检查重复
    // 3.5. 获取手机的验证信息,文档未提供！！！
    // 3.6. 检查第三方登陆方式的OpenId是否存在
    // 3.7. 生成微信会员（仅供微信调用)
    // 3.8. 第三方账号绑定已有会员
    // 3.9. 微会员完善资料 并升级为网站会员
    // 3.10. 接口方式注册
    // 3.11. 接口方式注册验证
    
    /*会员登陆与修改相关接口方法*/
    // 4.1. 会员登录
    // 4.2. 第三方账号登陆
    // 4.3. 会员信息修改（地址、密码）
    // 4.4. 更新会员基础信息
    // 4.5. 验证方式修改手机号码申请
    // 4.6. 验证方式修改手机号码确认
    // 4.7. 验证方式修改邮箱申请
    // 4.8. 验证方式修改邮箱确认
    // 4.9. 非验证方式修改联系方式
    // 4.10. 会员自动绑定第三方账号
    // 4.11. 取消第三方账号绑定
    // 4.12. 找回密码
    // 4.13. 旧卡激活
    // 4.14. 激活初始状态会员卡
    // 4.15. 申请短信验证
    // 4.16. 验证短信验证码

    /*积分相关接口方法*/
    // 5.1. 获取积分余额
    // 5.2. 获取会员积分列表
    // 5.3. 触发积分促销活动来获得积分
    // 5.4. 会员卡增加积分
    // 5.5. 会员卡积分使用
    // 5.6. 物品兑换
   
}
