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

    /*打印调试*/
    public static function p($arr, $pass = 0)
    {
        echo "<pre>";
        print_r($arr);
        // var_dump($arr);
        echo "</pre>";
        if(!$pass){
            die;
        }
    }

}