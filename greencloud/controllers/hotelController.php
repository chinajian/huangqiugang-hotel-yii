<?php
namespace greencloud\controllers;

use Yii;
use greencloud\controllers\BasicController;
use \Curl\Curl;
use libs\Tools;
use libs\GreencloudInfo;

class HotelController extends BasicController
{
	/*1-查询酒店信息*/
	public function actionHotels()
    {
    	$param = array(
    		'appKey' => $this->appKey,
		    'sessionId' => GreencloudInfo::getSessionid(),
		    'hotelGroupCode' => $this->hotelGroupCode,
    	);
    	$param['sign'] = $this->computeSign($param);

    	$curl = new Curl();
		$curl->post(Yii::$app->params['api']['hotel'].'hotels', $param);
		if ($curl->error) {
		    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
		} else {
		    Tools::p($curl->response);
		}
    }

    /*2-查询房型列表*/
    public function actionRoomList()
    {
    	$post = array(
    		'hotelGroupId' => 2,//集团ID（1-查询酒店信息接口中获取hotelGroupId）
    		'hotelId' => 9,//酒店ID（1-查询酒店信息接口中获取,id）
    	);
    	$param = array(
    		'appKey' => $this->appKey,
	    	'sessionId' => GreencloudInfo::getSessionid(),
		    'hotelGroupId' => $post['hotelGroupId'],
		    'hotelId' => (int)$post['hotelId'],
    	);
    	$param['sign'] = $this->computeSign($param);

    	$curl = new Curl();
		$curl->post(Yii::$app->params['api']['hotel'].'roomList', $param);
		if ($curl->error) {
		    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
		} else {
		    Tools::p($curl->response);
		}
    }

    /*3-查询房价房量*/
    public function actionQueryHotelList()
    {
    	$post = array(
    		'date' => '2018-06-22',//到店日期
    		'dayCount' => 8,//入住天数
    		'cityCode' => 'HZZJ1',//城市代码（1-查询酒店信息接口中获取cityCode）
    		'rateCodes' => '',//指定房价码
    		'hotelGroupId' => 2,//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    	);
    	$param = array(
    		'appKey' => $this->appKey,
		    'sessionId' => GreencloudInfo::getSessionid(),
    		'date' => $post['date'],//到店日期
    		'dayCount' => $post['dayCount'],//入住天数
    		'cityCode' => $post['cityCode'],//城市代码（1-查询酒店信息接口中获取cityCode）
    		'rateCodes' => $post['rateCodes'],//指定房价码
    		'salesChannel' => 'WEB',//渠道代码
    		'hotelIds' => $this->hotelId,
    		'hotelGroupId' => $post['hotelGroupId'],//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    	);
    	$param['sign'] = $this->computeSign($param);
    	// Tools::p($param, 1);

    	$curl = new Curl();
		$curl->post(Yii::$app->params['api']['hotel'].'queryHotelList', $param);
		if ($curl->error) {
		    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
		} else {
		    Tools::p($curl->response);
		}
    }

    /*4-查询房量*/
    public function actionListRoomAvail()
    {
    	$post = array(
    		'hotelGroupId' => 2,//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    		'arr' => '2018-08-08',//到达日期
    		'dep' => '2018-08-09',//离开日期
    		'rmtype' => 'LT,SDK,SDT,SPK,SPT,DXK,DXT,EXK',//房型（2-查询酒店房型列表接口中获取,roomType）
    	);
    	$param = array(
    		'appKey' => $this->appKey,
		    'sessionId' => GreencloudInfo::getSessionid(),
    		'hotelGroupId' => $post['hotelGroupId'],//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    		'hotelId' => $this->hotelId,
    		'arr' => $post['arr'],//到达日期
    		'dep' => $post['dep'],//离开日期
    		'rmtype' => $post['rmtype'],//房型
    	);
    	$param['sign'] = $this->computeSign($param);
    	// Tools::p($param, 1);

    	$curl = new Curl();
		$curl->post(Yii::$app->params['api']['hotel'].'listRoomAvail', $param);
		if ($curl->error) {
		    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
		} else {
		    Tools::p($curl->response);
		}
    }

    /*5-查询每日房价*/
    public function actionRateQueryEveryDay()
    {
    	$post = array(
    		'hotelGroupId' => 2,//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    		'date' => '2018-08-08',//到达日期
    		'dayCount' => 1,//入住天数
    		'rmType' => 'LT,SDK,SDT,SPK,SPT,DXK,DXT,EXK',//房型（2-查询酒店房型列表接口中获取,roomType）
    		'rateCode' => 'BAR',//指定房价码（3-查询房价房量,rateCodes）
    	);
    	$param = array(
    		'appKey' => $this->appKey,
		    'sessionId' => GreencloudInfo::getSessionid(),
    		'hotelGroupId' => $post['hotelGroupId'],//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    		'hotelId' => $this->hotelId,
    		'date' => $post['date'],//到达日期
    		'dayCount' => $post['dayCount'],//入住天数
    		'rmType' => $post['rmType'],//房型
    		'rateCode' => $post['rateCode'],//指定房价码（3-查询房价房量,rateCodes）
    	);
    	$param['sign'] = $this->computeSign($param);
    	// Tools::p($param, 1);

    	$curl = new Curl();
		$curl->post(Yii::$app->params['api']['hotel'].'rateQueryEveryDay', $param);
		if ($curl->error) {
		    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
		} else {
		    Tools::p($curl->response);
		}
    }

    /*6-查找可用房*/
    public function actionListRoomsSaleable()
    {
    	$post = array(
    		'hotelGroupId' => 2,//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    		'arr' => '2018-08-08',//到达日期
    		'dep' => '2018-08-09',//离开日期
    		'rmtype' => 'LT,SDK,SDT,SPK,SPT,DXK,DXT,EXK',//房型（2-查询酒店房型列表接口中获取,roomType）
    		'roomsFilter' => '',//房号过滤
    		'isClean' => '',//是否仅干净房
    	);
    	$param = array(
    		'appKey' => $this->appKey,
		    'sessionId' => GreencloudInfo::getSessionid(),
    		'hotelGroupId' => $post['hotelGroupId'],//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    		'hotelId' => $this->hotelId,
    		'arr' => $post['arr'],//到达日期
    		'dep' => $post['dep'],//离开日期
    		'rmtype' => $post['rmtype'],//房型
    		'roomsFilter' => $post['roomsFilter'],//房号过滤
    		'isClean' => $post['isClean'],//是否仅干净房
    	);
    	$param['sign'] = $this->computeSign($param);
    	// Tools::p($param, 1);

    	$curl = new Curl();
		$curl->post(Yii::$app->params['api']['hotel'].'listRoomsSaleable', $param);
		if ($curl->error) {
		    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
		} else {
		    Tools::p($curl->response);
		}
    }

    /*7-创建订单*/
    public function actionBook()
    {
    	$post = array(
    		'hotelGroupId' => 2,//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    		'arr' => '2018-06-23 08:08:08',//到达日期
    		'dep' => '2018-06-25 17:08:08',//离开日期
    		'rmtype' => 'LT',//房型（2-查询酒店房型列表接口中获取,roomType）
    		'rateCode' => 'BAR',//指定房价码（3-查询房价房量,rateCodes）
    		'rmNum' => 1,//房数
    		'rsvMan' => '微微一笑',//预订人
    		'sex' => 2,//性别
    		'mobile' => '13915028709',//联系电话
    		'idType' => '身份证',//证件类型
    		'idNo' => '321088198510063898',//证件号码
    		'email' => '756010290@qq.com',//邮箱
    		'cardType' => '',//会员计划
    		'cardNo' => '',//会员卡号
    		'adult' => 2,//人数
    		'remark' => '',//备注
    		'salesChannel' => '',//销售渠道
    		'src' => '',//来源
    		'market' => '',//市场
    		'channel' => '',//渠道
    		'packages' => '',//包价
    		'everyDayRate' => '',//每日房价
    		'rsvManId' => '',//订房人id
    		'rsvCompanyId' => '',//订房单位id
    	);
    	$param = array(
    		'appKey' => $this->appKey,
		    'sessionId' => GreencloudInfo::getSessionid(),
    		'hotelGroupId' => $post['hotelGroupId'],//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    		'hotelId' => $this->hotelId,
    		'arr' => $post['arr'],//到达日期
    		'dep' => $post['dep'],//离开日期
    		'rmtype' => $post['rmtype'],//房型
    		'rateCode' => $post['rateCode'],//指定房价码（3-查询房价房量,rateCodes）
    		'rmNum' => $post['rmNum'],//房数
    		'rsvMan' => $post['rsvMan'],//预订人
    		'sex' => $post['sex'],//性别
    		'mobile' => $post['mobile'],//联系电话
    		'idType' => $post['idType'],//证件类型
    		'idNo' => $post['idNo'],//证件号码
    		'email' => $post['email'],//邮箱
    		'cardType' => $post['cardType'],//会员计划
    		'cardNo' => $post['cardNo'],//会员卡号
    		'adult' => $post['adult'],//人数
    		'remark' => $post['remark'],//备注
    		'salesChannel' => $post['salesChannel'],//销售渠道
    		'src' => $post['src'],//来源
    		'market' => $post['market'],//市场
    		'channel' => $post['channel'],//渠道
    		'packages' => $post['packages'],//包价
    		'everyDayRate' => $post['everyDayRate'],//每日房价
    		'rsvManId' => $post['rsvManId'],//订房人id
    		'rsvCompanyId' => $post['rsvCompanyId'],//订房单位id
    	);
    	$param['sign'] = $this->computeSign($param);
    	// Tools::p($param, 1);

    	$curl = new Curl();
		$curl->post(Yii::$app->params['api']['hotel'].'book', $param);
		if ($curl->error) {
		    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
		} else {
		    Tools::p($curl->response);
		}
    }

    /*8-排房*/
    public function actionRoomArrangementByCrsNo()
    {
    	$post = array(
    		'hotelGroupId' => 2,//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    		'crsno' => '',//中央预订号
    		'rmtype' => 'LT,SDK,SDT,SPK,SPT,DXK,DXT,EXK',//房型（2-查询酒店房型列表接口中获取,roomType）
    		'rmnos' => '0823',//房号（6-查找可用房接口中获取,code）
    		'channel' => '',//渠道
    		'channelInnerNo' => '',//渠道内部编号
    	);
    	$param = array(
    		'appKey' => $this->appKey,
		    'sessionId' => GreencloudInfo::getSessionid(),
    		'hotelGroupId' => $post['hotelGroupId'],//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    		'hotelId' => $this->hotelId,
    		'crsno' => $post['crsno'],//中央预订号
    		'rmtype' => $post['rmtype'],//房型
    		'rmnos' => $post['rmnos'],//房号（6-查找可用房接口中获取,code）
    		'channel' => $post['channel'],//渠道
    		'channelInnerNo' => $post['channelInnerNo'],//渠道内部编号
    	);
    	$param['sign'] = $this->computeSign($param);
    	// Tools::p($param, 1);

    	$curl = new Curl();
		$curl->post(Yii::$app->params['api']['hotel'].'roomArrangementByCrsNo', $param);
		if ($curl->error) {
		    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
		} else {
		    Tools::p($curl->response);
		}
    }

    /*9-换房*/
    public function actionAssignRoom()
    {
    	$post = array(
    		'hotelGroupId' => 2,//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    		'crsNo' => '',//中央预订号
    		'oldRmnos' => '',//原房号
    		'newRmnos' => '',//新房号
    	);
    	$param = array(
    		'appKey' => $this->appKey,
		    'sessionId' => GreencloudInfo::getSessionid(),
    		'hotelGroupId' => $post['hotelGroupId'],//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    		'crsNo' => $post['crsNo'],//中央预订号
    		'oldRmnos' => $post['oldRmnos'],//原房号
    		'newRmnos' => $post['newRmnos'],//新房号
    	);
    	$param['sign'] = $this->computeSign($param);
    	// Tools::p($param, 1);

    	$curl = new Curl();
		$curl->post(Yii::$app->params['api']['hotel'].'assignRoom', $param);
		if ($curl->error) {
		    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
		} else {
		    Tools::p($curl->response);
		}
    }

    /*10-网站付款*/
    public function actionSaveWebPay()
    {
    	$post = array(
    		'hotelGroupId' => 2,//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    		'crsNo' => '',//中央预订号
    		'money' => 888,//金额
    		'taNo' => '123456789',//单据号
    		'taCode' => '',//入账代码
    		'taRemark' => '定房',//入账备注
    	);
    	$param = array(
    		'appKey' => $this->appKey,
		    'sessionId' => GreencloudInfo::getSessionid(),
    		'hotelGroupId' => $post['hotelGroupId'],//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    		'crsNo' => $post['crsNo'],//中央预订号
    		'money' => $post['money'],//金额
    		'taNo' => $post['taNo'],//单据号
    		'taCode' => $post['taCode'],//入账代码
    		'taRemark' => $post['taRemark'],//入账备注
    	);
    	$param['sign'] = $this->computeSign($param);
    	// Tools::p($param, 1);

    	$curl = new Curl();
		$curl->post(Yii::$app->params['api']['hotel'].'saveWebPay', $param);
		if ($curl->error) {
		    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
		} else {
		    Tools::p($curl->response);
		}
    }

    /*11-取消订单*/
    public function actionCancelbook()
    {
    	$post = array(
    		'hotelGroupId' => 2,//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    		'crsNo' => '',//中央预订号
    		'cardNo' => '',//会员卡号
    		'remark' => '',//备注
    	);
    	$param = array(
    		'appKey' => $this->appKey,
		    'sessionId' => GreencloudInfo::getSessionid(),
    		'hotelGroupId' => $post['hotelGroupId'],//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    		'crsNo' => $post['crsNo'],//中央预订号
    		'cardNo' => $post['cardNo'],//会员卡号
    		'remark' => $post['remark'],//备注
    	);
    	$param['sign'] = $this->computeSign($param);
    	// Tools::p($param, 1);

    	$curl = new Curl();
		$curl->post(Yii::$app->params['api']['hotel'].'cancelbook', $param);
		if ($curl->error) {
		    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
		} else {
		    Tools::p($curl->response);
		}
    }

    /*12-查询房间状态*/
    public function actionListRoomSta()
    {
    	$post = array(
    		'hotelGroupId' => 2,//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    		'sta' => 'VR',//指定状态
    		'flag' => 'T',//标记
    		'currentPage' => 'T',//当前页
    		'pageSize' => 20,//
    	);
    	$param = array(
    		'appKey' => $this->appKey,
		    'sessionId' => GreencloudInfo::getSessionid(),
    		'hotelGroupId' => $post['hotelGroupId'],//集团编号（1-查询酒店信息接口中获取hotelGroupId）
    		'hotelId' => $this->hotelId,
    		'sta' => $post['sta'],//指定状态
    		'flag' => $post['flag'],//标记
    		'currentPage' => $post['currentPage'],//当前页
    		'pageSize' => $post['pageSize'],//每页显示数量
    	);
    	$param['sign'] = $this->computeSign($param);
    	// Tools::p($param, 1);

    	$curl = new Curl();
		$curl->post(Yii::$app->params['api']['hotel'].'listRoomSta', $param);
		if ($curl->error) {
		    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
		} else {
		    Tools::p($curl->response);
		}
    }
   
}
