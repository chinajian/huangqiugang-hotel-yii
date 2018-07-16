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
    <h5 class="nav pull-left">系统设置</h5>
</nav>
<form class="form-horizontal" id="sysForm">
    <div class="form-group">
        <label for="sys_name" class="col-sm-2 control-label">应用名称</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="SysConfig[sys_name]" id="sys_name" placeholder="应用名称" value="<?php echo $sysConfig['sys_name']?>">
        </div>
    </div>
    <div class="form-group">
        <label for="sys_ename" class="col-sm-2 control-label">应用英文名称</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="SysConfig[sys_ename]" id="sys_ename" placeholder="应用名称" value="<?php echo $sysConfig['sys_ename']?>">
        </div>
    </div>
    <div class="form-group">
        <label for="albums" class="col-sm-2 control-label">轮播图</label>
        <div class="col-sm-9">
            <ul class="upload" id="albums" data-uploadid="albums">
                <?php 
                    $i = 0;
                    foreach($sysConfig['albums'] as $k => $v){
                ?>
                    <li class="full"><img src="<?php echo $v?>"><span class="del-img"></span></li>
                <?php $i++;}?>
                <?php for($j=0; $j<(5-$i); $j++){?>
                    <li class="empty"></li>
                <?php }?>
            </ul>
        </div>
    </div>
    <div class="form-group">
        <label for="hot_search" class="col-sm-2 control-label">热搜的关键字</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="SysConfig[hot_search]" id="hot_search" placeholder="热搜的关键字" value="<?php echo $sysConfig['hot_search']?>">
            <span class="help-block">多个关键字请用空格隔开</span>
        </div>
    </div>
    <div class="form-group">
        <label for="introduce" class="col-sm-2 control-label">介绍</label>
        <div class="col-sm-9">
            <script class="editor" id="introduce" name="SysConfig[introduce]" type="text/plain" style="height: 250px;"><?php echo $sysConfig['introduce']?></script>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-10 col-md-offset-2">
            <button type="button" class="btn btn-primary btn-sm" id="mod"><span class="glyphicon glyphicon-ok"></span> 提交</button>
        </div>
    </div>
</form>
<script type="text/javascript">
    /*创建编辑器*/
    var editorObj = 'introduce';
    UE.getEditor(editorObj, {
        autoHeightEnabled: true,
        autoFloatEnabled: true
    });

    /*修改*/
    $("#mod").click(function(){
        /*相册图片，循环出一个input列表>>>*/
            $('#albums').nextAll().remove();
            $('#albums').find('.full').each(function(){
                $('#albums').parent().append(`<input type="hidden" name="SysConfig[albums][]" value="`+ $(this).find('img').attr('src') +`">`);
            })
        /*相册图片，循环出一个input列表<<<*/
        jajax("<?php echo Url::to(['set_sys/index'])?>", $('#sysForm').serialize());
    })

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
                    if (imgUrl.substr(0,1) == '.'){
                        imgUrl = imgUrl.substr(1);
                    }
                    if(imgUrl){
                        $(self).attr('class', 'full');
                        $(self).html('<img src="' + imgUrl + '"><span class="del-img">')
                    };
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
</script>
<!-- 编辑器按钮 -->
<script type="text/javascript" charset="utf-8" src="<?php echo ADMIN_SITE_URL;?>ueditor1_4_3_3/addCustomizeButton.js"></script>