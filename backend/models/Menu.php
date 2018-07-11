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
            // ['c', 'required', 'message' => '控制器名称不能为空'],
            ['c', 'string', 'max' => 16],
            // ['a', 'required', 'message' => '方法名称不能为空'],
            ['a', 'string', 'max' => 16],
            ['data', 'string', 'max' => 128],
            ['sort', 'integer', 'message' => '父ID格式不正确'],
            ['display', 'in', 'range' => ['0', '1'],  'message' => '是否显示格式不正确'],
        ];
    }

    /*
    添加菜单
    $data   提交的数据
    */
    public function addMenu($data)
    {
        // P($data);
        if($this->load($data) and $this->validate()){
            if($this->save(false)){
                return true;
            }
        }
        return false;
    }


    /*
    修改菜单
    $id     
    $data   提交的数据
    */
    public function modMenu($id, $data)
    {
        if($this->load($data) and $this->validate()){
            $menu = self::find()->where('id = :uid', [':uid' => $id])->one();
            if(empty($menu)){//没有找到对应记录
               return false; 
            };
            // P($menu);
            if(isset($data['Menu']['name']) and !empty($data['Menu']['name'])){
                $menu->name = $data['Menu']['name'];
            }
            if(isset($data['Menu']['icon']) and !empty($data['Menu']['icon'])){
                $menu->icon = $data['Menu']['icon'];
            }
            if(isset($data['Menu']['parentid']) and !empty($data['Menu']['parentid'])){
                $menu->parentid = $data['Menu']['parentid'];
            }
            if(isset($data['Menu']['m']) and !empty($data['Menu']['m'])){
                $menu->m = $data['Menu']['m'];
            }
            if(isset($data['Menu']['c']) and !empty($data['Menu']['c'])){
                $menu->c = $data['Menu']['c'];
            }
            if(isset($data['Menu']['a']) and !empty($data['Menu']['a'])){
                $menu->a = $data['Menu']['a'];
            }
            if(isset($data['Menu']['data']) and !empty($data['Menu']['data'])){
                $menu->data = $data['Menu']['data'];
            }
            if(isset($data['Menu']['sort']) and !empty($data['Menu']['sort'])){
                $menu->sort = $data['Menu']['sort'];
            }
            if(isset($data['Menu']['display'])){
                $menu->display = $data['Menu']['display'];
            }
            if($menu->save(false)){
                return true;
            };
        }
        return false;
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
            $hasTotal = self::getHasNumById($arr, $arr[$i]["id"]);//此ID下有多少条数据(不包含隐藏的菜单)
            $endTotal = self::getEndNumById($arr, $arr[$i]["id"]);//此ID下有多少条数据(包含隐藏的菜单)
            if($arr[$i]["parentid"] == $parentid){
                if($hasTotal){//如果下面有子菜单(不包含隐藏的菜单)
                    $arr[$i]["has"] = true;
                }else{
                    $arr[$i]["has"] = false;
                }
                if($endTotal){//如果下面有子菜单(包含隐藏的菜单)
                    $arr[$i]["end"] = true;
                }else{
                    $arr[$i]["end"] = false;
                }
                array_push($list, $arr[$i]);
                self::setTreeList($arr, $arr[$i]["id"]);
            }
        }
        $level--;
        return $list;
    }
    /*
    为递归函数服务
    查看父id为 $parentid 的 结果有多少个(不包含隐藏的菜单)
    $arr        匹配的数组
    $parentid   匹配的父ID
    */
    private static function getHasNumById($arr, $parentid)
    {
        $num = 0;
        foreach($arr as $k => $v){
            if(($v["parentid"] == $parentid) && $v["display"]){
                $num++;
            }
        }
        return $num;
    }
    /*
    为递归函数服务
    查看父id为 $parentid 的 结果有多少个(不包含隐藏的菜单)
    $arr        匹配的数组
    $parentid   匹配的父ID
    */
    private static function getEndNumById($arr, $parentid)
    {
        $num = 0;
        foreach($arr as $k => $v){
            if(($v["parentid"] == $parentid)){
                $num++;
            }
        }
        return $num;
    }



    //------------------VUE TREE(因为目录结构不一样，VUE TREE子菜单是children包含的，所以不能通用上面的getTreeList结构)-----------------
    /*
    取出树形分类
    */
    public static function getVueTreeList()
    {
        $menuList = self::find()->select(['id', 'name as label', 'parentid'])->asArray()->all();
        $menuList = self::setVueTreeList($menuList);
        return $menuList;
    }
    /*
    递归函数
    返回一个 商品分类 树列表
    $arr        需要处理的数组
    $parentid   父ID
    */
    private static function setVueTreeList($arr, $parentid=0)
    {
        $list = array();
        $j = 0;

        for($i=0; $i<count($arr); $i++){
            if($arr[$i]["parentid"] == $parentid){
                array_push($list, $arr[$i]);
                // $list[$j]['children'] = array();
                $list[$j]['children'] = self::setVueTreeList($arr, $arr[$i]["id"]);
                $j++;
            }
        }
        return $list;
    }
    
}
