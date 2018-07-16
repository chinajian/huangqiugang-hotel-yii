<?php
namespace libs;

use Yii;
/*
工具类
*/
class Tools
{

	/*返回函数*/
    public static function showRes($code = 0, $msg = 'ok', $data = '')
    {
        $res = array(
            "code" => $code,
            'msg' => $msg,
        );

        if(!empty($data)){
            $res['data'] = $data;
        }
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    /*
    根据尺寸，返回对应的图片
    如果没有对应的尺寸图，就返回原始图，如果原始图
    $Imgurl     需要转换的图片路径
    $size       需要转换的图片尺寸
    */
    public static function getImgBySize($Imgurl, $size='thumb'){
        $size = $size.'_img';
        $sizes = Yii::$app->params['imgSize'];//所有配置尺寸
        $suffix = explode('.', $Imgurl)[1];
        $tmp = explode('!!', $Imgurl);
        if(count($tmp)>1){//有裁剪尺寸
            $tmp2 = explode('_', $tmp[1]);
            $len = $tmp2[0];//此图片有多少尺寸
            $i = count($sizes);
            foreach(array_reverse($sizes) as $k => $v){
                if($k == $size){
                    if($i <= $len){
                        $newImgurl = $tmp[0].'!!'.$len.'_'.$sizes[$size].'x'.$sizes[$size].'.'.$suffix;
                    }
                }
                $i--;
            }
        };
        $Imgurl = $tmp[0].'.'.$suffix;
        return isset($newImgurl)?$newImgurl:$Imgurl;
    }

}