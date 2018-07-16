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

<link rel="stylesheet" href="<?php echo ADMIN_SITE_URL;?>select2/select2.min.css"/>
<script type="text/javascript" charset="utf-8" src="<?php echo ADMIN_SITE_URL;?>select2/select2.full.min.js"></script>
<?php 
    use yii\helpers\Url;
?>
<nav class="navbar navbar-default child-nav">
    <h5 class="nav pull-left">添加商品</h5>
    <div class="nav pull-right">
        <a href="<?php echo yii\helpers\Url::to(['goods/goods-list'])?>" type="button" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-th-list"></span> 商品列表</a>
    </div>
</nav>
<form class="form-horizontal" id="addForm">
    <div class="form-group">
        <label for="goods_name" class="col-sm-2 control-label">商品标题<span class="mandatory">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="Goods[goods_name]" id="goods_name" placeholder="商品标题">
        </div>
    </div>
    <div class="form-group">
        <label for="goods_sn" class="col-sm-2 control-label">商品货号</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="Goods[goods_sn]" id="goods_sn" placeholder="商品货号">
            <span class="help-block">如果不填写，系统将生成唯一货号</span>
        </div>
    </div>
    <div class="form-group">
        <label for="integral" class="col-sm-2 control-label">所需积分</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="Goods[integral]" id="integral" placeholder="所需积分">
        </div>
    </div>
    <div class="form-group">
        <label for="album_img" class="col-sm-2 control-label">上传图片</label>
        <div class="col-sm-9">
            <ul class="upload" id="upload">
                <li class="empty"></li>
                <li class="empty"></li>
                <li class="empty"></li>
                <li class="empty"></li>
                <li class="empty"></li>
            </ul>
        </div>
    </div>
    <div class="form-group">
        <label for="is_new" class="col-sm-2 control-label">是否新品</label>
        <div class="col-sm-9 iCheck">
            <label class="radio-inline">
                <input type="radio" name="Goods[is_new]" value="1"> 否
            </label>
            <label class="radio-inline">
                <input type="radio" name="Goods[is_new]" value="2" checked> 是
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="is_best" class="col-sm-2 control-label">是否精品</label>
        <div class="col-sm-9 iCheck">
            <label class="radio-inline">
                <input type="radio" name="Goods[is_best]" value="1"> 否
            </label>
            <label class="radio-inline">
                <input type="radio" name="Goods[is_best]" value="2" checked> 是
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="is_hot" class="col-sm-2 control-label">是否热销</label>
        <div class="col-sm-9 iCheck">
            <label class="radio-inline">
                <input type="radio" name="Goods[is_hot]" value="1"> 否
            </label>
            <label class="radio-inline">
                <input type="radio" name="Goods[is_hot]" value="2" checked> 是
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="keywords" class="col-sm-2 control-label">关键字</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="Goods[keywords]" id="keywords" placeholder="关键字">
            <span class="help-block">多个关键字请用空格隔开</span>
        </div>
    </div>
    <div class="form-group">
        <label for="sort" class="col-sm-2 control-label">排序</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="Goods[sort]" id="sort" placeholder="排序">
        </div>
    </div>
    <div class="form-group">
        <label for="goods_brief" class="col-sm-2 control-label">描述</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="Goods[goods_brief]" id="goods_brief" rows="2"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="goods_desc" class="col-sm-2 control-label">详细说明<span class="mandatory">*</span></label>
        <div class="col-sm-9">
            <script class="editor" id="goods_desc" name="Goods[goods_desc]" type="text/plain" style="height: 150px;"></script>
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
//-------------------------------------------------------------------------------------------------------------------

    /*创建编辑器-------------------------------------------------------------------*/
    var editorObj = 'goods_desc';
    UE.getEditor(editorObj, {
        autoHeightEnabled: true,
        autoFloatEnabled: true
    });

    /*添加商品----------------------------------------------------------------------*/
    $("#add").click(function(){
        /*相册图片，循环出一个input列表>>>*/
            $('#upload').nextAll().remove();
            $('#upload').find('.full').each(function(){
                $('#upload').parent().append(`<input type="hidden" name="Goods[album_img][]" value="`+ $(this).find('img').attr('src') +`">`);
            })
        /*相册图片，循环出一个input列表<<<*/
        jajax("<?php echo Url::to(['goods/add-goods'])?>", $('#addForm').serialize());
    })

    /*图片 上传--------------------------------------------------------------------*/
    $("#upload li").click(function(event){
        /*删除图片*/
        if(event.originalEvent.target.tagName.toLowerCase() === 'span'){//点的是span关闭按钮
            $(this).children().fadeOut(function () {
                $(this).parent().attr('class', 'empty');
                $(this).remove();
                $("#img_url").val('');
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
                    $("#img_url").val(imgUrl);
                }
            }
        }else if($(this).attr('class') === 'empty'){
            var args = {
                'url': '<?php echo Url::to(['basic/upload-file'])?>',
                'count': $("#upload li.empty").length,
                'fn': function(res){
                    $('#upload li.empty').each(function(index, element){
                        var imgUrl = res.eq(index).find('img').attr('src');
                        if(imgUrl){
                            $(this).attr('class', 'full');
                            $(this).html('<img src="' + imgUrl + '"><span class="del-img">')
                        }
                    });

                    var shopLogoArr = new Array();
                    $('#upload li.full').each(function(index, element){
                        shopLogoArr.push($(this).find('img').attr('src'));
                    });
                    shopLogo = shopLogoArr.join(',');
                    $("#img_url").val(shopLogo);

                }
            }
        }
        openUploadLayer(args);
    });

</script>
<!-- 编辑器按钮 -->
<script type="text/javascript" charset="utf-8" src="<?php echo ADMIN_SITE_URL;?>ueditor1_4_3_3/addCustomizeButton.js"></script>