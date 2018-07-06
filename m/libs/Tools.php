<?php
namespace libs;
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

}