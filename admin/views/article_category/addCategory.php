<?php 
    use yii\helpers\Url;
?>
<nav class="navbar navbar-default child-nav">
    <h5 class="nav pull-left">添加推荐分类</h5>
    <div class="nav pull-right">
        <a href="<?php echo yii\helpers\Url::to(['article_category/category-list'])?>" type="button" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-th-list"></span> 推荐分类列表</a>
    </div>
</nav>
<form class="form-horizontal" id="addForm">
    <div class="form-group">
        <label for="cat_name" class="col-sm-2 control-label">分类名称<span class="mandatory">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="ArticleCategory[cat_name]" id="cat_name" placeholder="分类名称">
        </div>
    </div>
    <div class="form-group">
        <label for="parent_id" class="col-sm-2 control-label">上级分类</label>
        <div class="col-sm-9">
            <select class="form-control" name="ArticleCategory[parent_id]" id="parent_id">
                <option value="0">顶级分类</option>
                <?php foreach($categoryList as $k => $v){?>
                    <option value="<?php echo $v['cat_id'];?>"><?php echo $v['cat_name'];?></option>
                <?php }?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="keywords" class="col-sm-2 control-label">关键字</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="ArticleCategory[keywords]" id="keywords" placeholder="关键字">
            <span class="help-block">多个关键字请用空格隔开</span>
        </div>
    </div>
    <div class="form-group">
        <label for="link" class="col-sm-2 control-label">外部链接</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="ArticleCategory[link]" id="link" placeholder="外部链接">
        </div>
    </div>
    <div class="form-group">
        <label for="cat_desc" class="col-sm-2 control-label">描述</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="ArticleCategory[cat_desc]" id="cat_desc" rows="2"></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-10 col-md-offset-2">
            <button type="reset" class="btn btn-default btn-sm" data-dismiss="modal"><span class="glyphicon glyphicon-repeat"></span> 重置</button>
            <button type="button" class="btn btn-primary btn-sm" id="add"><span class="glyphicon glyphicon-ok"></span> 提交</button>
        </div>
    </div>
</form>
<script type="text/javascript">
    /*添加*/
    $("#add").click(function(){
        jajax("<?php echo Url::to(['article_category/add-category'])?>", $('#addForm').serialize());
    })
</script>