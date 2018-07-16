<script src="<?php echo ADMIN_SITE_URL;?>datetimepicker/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo ADMIN_SITE_URL;?>datetimepicker/bootstrap-datetimepicker.zh-CN.js"></script>
<link rel="stylesheet" href="<?php echo ADMIN_SITE_URL;?>datetimepicker/bootstrap-datetimepicker.min.css"/>

<link rel="stylesheet" href="<?php echo COMMON_SITE_URL;?>webuploader/webuploader.css"/>
<link rel="stylesheet" href="<?php echo ADMIN_SITE_URL;?>j-uploader/upload.css"/>
<link rel="stylesheet" href="<?php echo ADMIN_SITE_URL;?>css/upload.css"/>
<script src="<?php echo COMMON_SITE_URL;?>webuploader/webuploader.min.js"></script>
<script src="<?php echo ADMIN_SITE_URL;?>j-uploader/upload.js"></script>

<script type="text/javascript" charset="utf-8" src="<?php echo ADMIN_SITE_URL;?>ueditor1_4_3_3/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo ADMIN_SITE_URL;?>ueditor1_4_3_3/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo ADMIN_SITE_URL;?>ueditor1_4_3_3/lang/zh-cn/zh-cn.js"></script>
<?php 
    use yii\helpers\Url;
?>
<nav class="navbar navbar-default child-nav">
    <h5 class="nav pull-left">添加推荐</h5>
    <div class="nav pull-right">
        <a href="<?php echo yii\helpers\Url::to(['article/article-list'])?>" type="button" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-th-list"></span> 推荐列表</a>
    </div>
</nav>
<form class="form-horizontal" id="addForm">
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">推荐标题<span class="mandatory">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="Article[title]" id="title" placeholder="推荐标题">
        </div>
    </div>
    <div class="form-group">
        <label for="cat_id" class="col-sm-2 control-label">推荐分类</label>
        <div class="col-sm-9">
            <select class="form-control" name="Article[cat_id]" id="cat_id">
                <option value="">请选择分类</option>
                <?php foreach($categoryList as $k => $v){?>
                    <option value="<?php echo $v['cat_id'];?>"><?php echo $v['cat_name'];?></option>
                <?php }?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="thumb" class="col-sm-2 control-label">预览图</label>
        <div class="col-sm-9">
            <ul class="upload" data-uploadid="thumb">
                <li class="empty"></li>
            </ul>
            <input type="hidden" name="Article[thumb]" id="thumb">
        </div>
    </div>
    <div class="form-group">
        <label for="author" class="col-sm-2 control-label">营业时间</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="Article[author]" id="author" placeholder="营业时间">
        </div>
    </div>
    <div class="form-group">
        <label for="copyfrom" class="col-sm-2 control-label">来源</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="Article[copyfrom]" id="copyfrom" placeholder="来源">
        </div>
    </div>
    <div class="form-group">
        <label for="keywords" class="col-sm-2 control-label">关键字</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="Article[keywords]" id="keywords" placeholder="关键字">
            <span class="help-block">多个关键字请用空格隔开</span>
        </div>
    </div>
    <div class="form-group">
        <label for="article_type" class="col-sm-2 control-label">推荐置顶</label>
        <div class="col-sm-9 iCheck">
            <label class="radio-inline">
                <input type="radio" name="Article[article_type]" value="0"> 普通
            </label>
            <label class="radio-inline">
                <input type="radio" name="Article[article_type]" value="1"> 置顶
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="status" class="col-sm-2 control-label">推荐状态</label>
        <div class="col-sm-9 iCheck">
            <label class="radio-inline">
                <input type="radio" name="Article[status]" value="0"> 不显示
            </label>
            <label class="radio-inline">
                <input type="radio" name="Article[status]" value="1"> 显示
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="link" class="col-sm-2 control-label">外部链接</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="Article[link]" id="link" placeholder="外部链接">
        </div>
    </div>
    <div class="form-group">
        <label for="sort" class="col-sm-2 control-label">排序</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="Article[sort]" id="sort" placeholder="排序">
        </div>
    </div>
    <div class="form-group">
        <label for="add_time" class="col-sm-2 control-label">添加时间</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm form_datetime" name="Article[add_time]" id="add_time" placeholder="添加时间" readonly>
            <span class="help-block">默认当前时间</span>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-2 control-label">描述</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="Article[description]" id="description" rows="2"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="content" class="col-sm-2 control-label">内容</label>
        <div class="col-sm-9">
            <script class="editor" id="content" name="Article[content]" type="text/plain" style="height: 250px;"></script>
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
    /*选择发表日期*/
    $('.form_datetime').datetimepicker({
        language:  'zh-CN',
        format: "yyyy-mm-dd",
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        minView: 2,
        showMeridian: 1
    });

    /*创建编辑器*/
    var editorObj = 'content';
    UE.getEditor(editorObj, {
        autoHeightEnabled: true,
        autoFloatEnabled: true
    });

    /*图片 上传*/
    $(".upload li").click(function(event){
        var uploadDomName = $(this).parent().data('uploadid');
        // console.log(uploadDomName);
        /*删除图片*/
        if(event.originalEvent.target.tagName.toLowerCase() === 'span'){//点的是span关闭按钮
            $(this).children().fadeOut(function () {
                $(this).parent().attr('class', 'empty');
                $(this).remove();
                $("#"+uploadDomName).val('');
            });
            return;
        }

        if($(this).attr('class') === 'full'){
            var self = this;
            var args = {
                'url': '<?php echo Url::to(['basic/upload-file'])?>',
                'count': 1,
                'defaultImg': $(this).find('img').attr('src'),
                'fn': function(res){
                    var imgUrl = res.eq(0).find('img').attr('src');
                    if(imgUrl){
                        $(self).attr('class', 'full');
                        $(self).html('<img src="' + imgUrl + '"><span class="del-img">')
                    }
                    $("#"+uploadDomName).val(imgUrl);
                }
            }
        }else if($(this).attr('class') === 'empty'){
            var self = this;
            var args = {
                'url': '<?php echo Url::to(['basic/upload-file'])?>',
                // 'count': $(".upload li.empty").length,
                'count': $(self).parent().find('.empty').length,
                'fn': function(res){
                    $(self).parent().find('.empty').each(function(index, element){
                        var imgUrl = res.eq(index).find('img').attr('src');
                        if(imgUrl){
                            $(this).attr('class', 'full');
                            $(this).html('<img src="' + imgUrl + '"><span class="del-img">')
                        }
                    });

                    var shopLogoArr = new Array();
                    $(self).parent().find('.full').each(function(index, element){
                        shopLogoArr.push($(this).find('img').attr('src'));
                    });
                    shopLogo = shopLogoArr.join(',');
                    $("#"+uploadDomName).val(shopLogo);

                }
            }
        }
        openUploadLayer(args);
    });

    /*添加*/
    $("#add").click(function(){
        jajax("<?php echo Url::to(['article/add-article'])?>", $('#addForm').serialize());
    })

</script>
<!-- 编辑器按钮 -->
<script type="text/javascript" charset="utf-8" src="<?php echo ADMIN_SITE_URL;?>ueditor1_4_3_3/addCustomizeButton.js"></script>