<?php

namespace app\models;

use Yii;

class ArticleCategory extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%article_category}}';
    }

    public function rules()
    {
        return [
            ['cat_name', 'required', 'message' => '分类名称不能为空'],
            ['cat_name', 'string', 'max' => 30],
            ['cat_type', 'in', 'range' => [1, 2, 3, 4, 5], 'message' => '类型格式不正确'],
            ['parent_id', 'integer', 'message' => '父ID格式不正确'],
            ['link', 'string', 'max' => 100],
            ['keywords', 'string', 'max' => 255],
            ['cat_desc', 'string', 'max' => 255],
            ['sort', 'integer', 'message' => '排序格式不正确'],
            ['show_in_nav', 'in', 'range' => [0, 1], 'message' => '导航格式不正确'],
            [['hits', 'all_parent_id', 'child', 'all_child_id'], 'safe'],
        ];
    }


    /*添加类别*/
    public function addCategory($data)
    {
        if((int)$data['ArticleCategory']['parent_id'] === 0){
            /*说明是顶级分类*/
            if($this->load($data) and $this->validate()){
                if($this->save(false)){
                    /*写入日志*/
                    SysLog::addLog('添加文章分类['. $data['ArticleCategory']['cat_name'] .']成功');
                    return true;
                }
            }
            return false;
        }

        /*取出父元素的all_parent_id 为了完善自己的all_parent_id*/
        $category = self::find()->select('cat_id, parent_id, all_parent_id, all_child_id')->where('cat_id = :catid', [':catid' => $data['ArticleCategory']['parent_id']])->one();
        if(is_null($category)){
            $this->addError('parent_id', '上级分类不存在');
            // throw new \Exception();
            return false;
        }

        $data['ArticleCategory']['all_parent_id'] = $category->all_parent_id|$category->all_parent_id . ',' . $data['ArticleCategory']['parent_id'];//整理自身的all_parent_id
        if($this->load($data) and $this->validate()){
            $transaction = Yii::$app->db->beginTransaction();//事物处理
            try{
                $this->save(false);
                /*修改父元素的child 和 all_child_id*/
                /*循环自身的所有父元素ID,并修改*/
                for($i=0; $i<count($allParentIdArr = explode(',', $this->all_parent_id)); $i++){
                    if($allParentIdArr[$i]){
                        $category = self::find()->select('cat_id, parent_id, all_parent_id, all_child_id')->where('cat_id = :catid', [':catid' => $allParentIdArr[$i]])->one();
                        $category->child = 1;
                        $category->all_child_id = ($category->all_child_id?$category->all_child_id . ',':'') . $this->getPrimaryKey();
                        $category->save(false);
                    }
                }
                SysLog::addLog('添加文章分类['. $data['ArticleCategory']['cat_name'] .']成功');
                $transaction->commit();            
                return true;
            }catch(\Exception $e){
                $transaction->rollback();
                throw new \Exception();         
                return false;
            };
        }

    }

    /*修改类别*/
    public function modCategory($id, $data)
    {
        /*取出父元素的all_parent_id 为了完善自己的all_parent_id*/
        if($data['ArticleCategory']['parent_id']){//如果不是顶级分类，需要验证上级目录是否存在
            $parentCategory = self::find()->select('cat_id, parent_id, all_parent_id, all_child_id')->where('cat_id = :catid', [':catid' => $data['ArticleCategory']['parent_id']])->one();
            if(is_null($parentCategory)){
                $this->addError('parent_id', '上级分类不存在');
                return false;
            }
        }

        if($this->load($data) and $this->validate()){
            $articleCategory = self::find()->where('cat_id = :catid', [':catid' => $id])->one();
            if(is_null($articleCategory)){
                return false; 
            }
            if(($data['ArticleCategory']['parent_id'] == $articleCategory->cat_id) or (in_array($data['ArticleCategory']['parent_id'], explode(',', $articleCategory->all_child_id)))){//如果父级 选择的是自身，或者选择的是子级的话
                $this->addError('parent_id', '类别不能选择自身和子分类');
                return false;
            }

            $transaction = Yii::$app->db->beginTransaction();//事物处理
            try{
                if($articleCategory->parent_id === $data['ArticleCategory']['parent_id']){//如果没有修改分类
                    /*修改文章分类    B*/
                    $articleCategory->cat_name = $data['ArticleCategory']['cat_name'];
                    $articleCategory->parent_id = $data['ArticleCategory']['parent_id'];
                    $articleCategory->link = $data['ArticleCategory']['link'];
                    $articleCategory->keywords = $data['ArticleCategory']['keywords'];
                    $articleCategory->cat_desc = $data['ArticleCategory']['cat_desc'];
                    $articleCategory->save(false);//修改文章分类
                    /*修改文章分类    E*/
                }else{//同时修改了分类
                    /*修改之前所有父元素的all_child_id     B*/
                    /*其实就是去掉自己的cat_id 和自己子元素的cat_id，同时看看下面还有没有子元素，如果没有的话，child字段改成0*/
                    for($i=0; $i<count($allParentIdArr = explode(',', $articleCategory->all_parent_id)); $i++){
                        if($allParentIdArr[$i]){
                            $category = self::find()->select('cat_id, all_parent_id, child, all_child_id')->where('cat_id = :catid', [':catid' => $allParentIdArr[$i]])->one();
                            $tmpArr = explode(',', $category->all_child_id);
                            foreach ($tmpArr as $k => $v){
                                if (($v == $articleCategory->cat_id) or (in_array($v, explode(',', $articleCategory->all_child_id)))){
                                    unset($tmpArr[$k]);
                                }
                            }
                            if(count($tmpArr)){
                                $category->child = 1;
                            }else{
                                $category->child = 0;
                            }
                            $category->all_child_id = implode(',', $tmpArr);
                            $category->save(false);
                        }
                    }
                    /*修改之前所有父元素的all_child_id     E*/

                    if($data['ArticleCategory']['parent_id'] > 0){//如果父级是顶级分类
                        $data['ArticleCategory']['all_parent_id'] = $parentCategory->all_parent_id|$parentCategory->all_parent_id . ',' . $data['ArticleCategory']['parent_id'];//整理自身的all_parent_id
                    }else{
                        $data['ArticleCategory']['all_parent_id'] = '0';//整理自身的all_parent_id
                    }

                    /*修改文章分类    B*/
                    $articleCategory->cat_name = $data['ArticleCategory']['cat_name'];
                    $articleCategory->parent_id = $data['ArticleCategory']['parent_id'];
                    $articleCategory->all_parent_id = $data['ArticleCategory']['all_parent_id'];
                    $articleCategory->link = $data['ArticleCategory']['link'];
                    $articleCategory->keywords = $data['ArticleCategory']['keywords'];
                    $articleCategory->cat_desc = $data['ArticleCategory']['cat_desc'];
                    $articleCategory->save(false);//修改文章分类
                    /*修改文章分类    E*/

                    /*修改现在所有父元素的all_child_id     B*/
                    /*就是在所有父级 添加自身的cat_id 和子级的cat_id*/
                    for($i=0; $i<count($allParentIdArr = explode(',', $articleCategory->all_parent_id)); $i++){
                        if($allParentIdArr[$i]){
                            $category = self::find()->select('cat_id, all_parent_id, child, all_child_id')->where('cat_id = :catid', [':catid' => $allParentIdArr[$i]])->one();
                            $category->child = 1;
                            if($articleCategory->all_child_id){
                                $category->all_child_id = ($category->all_child_id?$category->all_child_id . ',':'') . $articleCategory->cat_id . ',' . $articleCategory->all_child_id;
                            }else{
                                $category->all_child_id = ($category->all_child_id?$category->all_child_id . ',':'') . $articleCategory->cat_id;
                            }
                            $category->save(false);
                        }
                    }
                    /*修改现在所有父元素的all_child_id     E*/


                    /*修改所有子元素的all_parent_id     B*/
                    for($i=0; $i<count($allChildIdArr = explode(',', $articleCategory->all_child_id)); $i++){
                        if($allChildIdArr[$i]){
                            $category = self::find()->select('cat_id, all_parent_id, child, all_child_id')->where('cat_id = :catid', [':catid' => $allChildIdArr[$i]])->one();
                            $category->all_parent_id = $articleCategory->all_parent_id . ',' . $articleCategory->cat_id;
                            $category->save(false);
                        }

                    }
                    /*修改所有子元素的all_parent_id     E*/
                }

                SysLog::addLog('修改文章分类['. $articleCategory->cat_name .']成功');
                $transaction->commit();  
                return true;
            }catch(\Exception $e){
                $transaction->rollback();
                throw new \Exception($e);         
                return false;
            };
            return false;
        }

    }


    /*删除类别*/
    public function delCategory($id)
    {
        $articleCategory = $this::find()->where('cat_id = :id', [':id' => $id])->one();

        /*查看该分类下有没有子类*/
        if($articleCategory->child){
            $this->addError('cat_name', '此分类下含有子分类！');
            return false;
        }

        /*查看该分类下有没有商品*/
        $goods = Goods::find()->where('find_in_set(:pid, cat_ids)', [':pid' => $id])->one();
        if(!is_null($goods)){
            $this->addError('cat_name', '此分类下含有商品！');
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();//事物处理
        try{
            /*修改所有上级目录的child字段和all_child_id字段(所有上级)>>>>*/
            for($i=0; $i<count($allParentIdArr = explode(',', $articleCategory->all_parent_id)); $i++){
                if($allParentIdArr[$i]){
                    $parent = self::find()->select('cat_id, all_parent_id, child, all_child_id')->where('cat_id = :catid', [':catid' => $allParentIdArr[$i]])->one();
                    if($parent->all_child_id == $articleCategory->cat_id){//说明上级目录只有一个子目录了
                        $parent->child = 0;
                        $parent->all_child_id = '';
                        // P($parent);
                    }else{
                        $tmpArr = explode(',', $parent->all_child_id);
                        foreach($tmpArr as $k => $v){
                            if($tmpArr[$k] == $articleCategory->cat_id){
                                array_remove($tmpArr, $k);
                            }
                        }
                        $parent->all_child_id = implode(',', $tmpArr);
                    };
                    $parent->save(false);
                }
            }
            /*修改所有上级目录的child字段和all_child_id字段(所有上级)<<<<*/

            $articleCategory->delete();
            /*写入日志*/
            SysLog::addLog('删除文章分类['. $articleCategory->cat_name .']成功');
            $transaction->commit();  
            return true;
        }catch(\Exception $e){
            $transaction->rollback();
            throw new \Exception($e);         
            return false;
        };
    }


    /*
    取出树形分类
    根据ID取出对应的数据
    $cat_id     分类ID
    */
    public static function getTreeList($cat_id=0)
    {
        $cat_id = (int)$cat_id;
        $articleList = self::find()->where(['like', 'all_parent_id', $cat_id])->asArray()->all();
        $articleList = self::setTreeList($articleList, $cat_id);
        return $articleList;
    }
    

    /*
    递归函数
    返回一个 商品分类 树列表

    $arr        需要处理的数组
    $parent_id  从哪个分类
    */
    private static function setTreeList($arr, $parent_id)
    {
        static $list = array();
        static $level = 0;//层级
        $level++;

        $total = self::getNumById($arr, $parent_id);//此ID下有多少条数据
        $tmp = 0;//一共匹配到几个
        for($i=0; $i<count($arr); $i++){
            $arr[$i]["level"] = $level;
            if($arr[$i]["parent_id"] == $parent_id){
                $tmp++;//匹配到之后加1
                if($tmp == $total){
                    $end = 0;
                }else{
                    $end = 1;
                }
                $arr[$i]["cat_name"] = self::getIndent($level, $end).$arr[$i]["cat_name"];
                array_push($list, $arr[$i]);
                self::setTreeList($arr, $arr[$i]["cat_id"]);
            }
        }
        $level--;
        return $list;
    }

    /*
    为递归函数服务 能够确定前面的符号
    查看父id为 $parent_id 的 结果有多少个
    $arr        匹配的数组
    $parent_id  匹配的父ID
    */
    private static function getNumById($arr, $parent_id)
    {
        $num = 0;
        foreach($arr as $k => $v){
            if($v["parent_id"] == $parent_id){
                $num++;
            }
        }
        return $num;
    }


    /*
    为递归函数服务
    返回缩进元素
    $level  等级
    $isEnd   是否到最后一个了，因为最后一个标识不一样
    │       ├─      └─
    */
    private static function getIndent($level, $isEnd = 1)
    {
        $gap = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|";//前面的空隙
        if($level == 1){
            return "";
        }
        if($isEnd == 1){
            $res = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├─";
        }else{
            $res = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└─";
        }
        for($i=2; $i<$level; $i++){
            $res = $gap.$res;
        }
        return $res;
    }

}
