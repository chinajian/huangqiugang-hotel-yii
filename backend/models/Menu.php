<?php

namespace app\models;

use Yii;

class Menu extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%menu}}';
    }

    public function rules()
    {
        return [
            ['name', 'required', 'message' => '菜单名称不能为空'],
            ['name', 'string', 'max' => 32],
            ['icon', 'string', 'max' => 16],
            ['parentid', 'integer', 'message' => '父ID格式不正确'],
            ['m', 'string', 'max' => 16],
            ['c', 'required', 'message' => '控制器名称不能为空'],
            ['c', 'string', 'max' => 16],
            ['a', 'required', 'message' => '方法名称不能为空'],
            ['a', 'string', 'max' => 16],
            ['data', 'string', 'max' => 128],
            ['sort', 'integer', 'message' => '父ID格式不正确'],
            ['display', 'in', 'range' => ['0', '1'],  'message' => '是否显示格式不正确'],
        ];
    }


    /*
    取出树形分类
    */
    public static function getTreeList()
    {
        $menuList = self::find()->asArray()->all();
        $menuList = self::setTreeList($menuList);
        return $menuList;
    }

    /*
    递归函数
    返回一个 商品分类 树列表
    $arr        需要处理的数组
    $parentid   父ID
    */
    private static function setTreeList($arr, $parentid=0)
    {
        static $list = array();
        static $level = 0;//层级
        $level++;

        for($i=0; $i<count($arr); $i++){
            $arr[$i]["level"] = $level;
            $total = self::getNumById($arr, $arr[$i]["id"]);//此ID下有多少条数据
            if($arr[$i]["parentid"] == $parentid){
                if($total){//如果下面有子栏目
                    $arr[$i]["has"] = true;
                }else{
                    $arr[$i]["has"] = false;
                }
                array_push($list, $arr[$i]);
                self::setTreeList($arr, $arr[$i]["id"]);
            }
        }
        $level--;
        return $list;
    }

    /*
    为递归函数服务 能够确定前面的符号
    查看父id为 $parentid 的 结果有多少个
    $arr        匹配的数组
    $parentid   匹配的父ID
    */
    private static function getNumById($arr, $parentid)
    {
        $num = 0;
        foreach($arr as $k => $v){
            if($v["parentid"] == $parentid){
                $num++;
            }
        }
        return $num;
    }
    
}
