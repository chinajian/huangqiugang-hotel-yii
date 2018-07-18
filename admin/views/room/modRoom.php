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
    <h5 class="nav pull-left">修改客房</h5>
    <div class="nav pull-right">
        <a href="<?php echo yii\helpers\Url::to(['room/room-list'])?>" type="button" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-th-list"></span> 客房列表</a>
    </div>
</nav>
<form class="form-horizontal" id="modForm">
    <input type="hidden" class="form-control input-sm" name="Room[room_id]" id="room_id" value="<?php echo $room['room_id']?>">
    <div class="form-group">
        <label for="room_name" class="col-sm-2 control-label">客房标题<span class="mandatory">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="Room[room_name]" id="room_name" placeholder="客房标题" value="<?php echo $room['room_name']?>">
        </div>
    </div>
    <div class="form-group">
        <label for="preview" class="col-sm-2 control-label">预览图</label>
        <div class="col-sm-9">
            <ul class="upload" data-uploadid="preview">
                <?php if(!empty($room['preview'])){?>
                    <li class="full"><img src="<?php echo $room['preview']?>"><span class="del-img"></span></li>
                <?php }else{?>
                    <li class="empty"></li>
                <?php }?>
            </ul>
            <input type="hidden" name="Room[preview]" id="preview" value="<?php echo $room['preview']?>">
        </div>
    </div>
    <div class="form-group">
        <label for="album_img" class="col-sm-2 control-label">相册列表</label>
        <div class="col-sm-9">
            <ul class="upload" id="upload">
                <?php 
                    $i = 0;
                    foreach($room['album_img'] as $k => $v){
                ?>
                    <li class="full"><img src="<?php echo $v?>"><span class="del-img"></span></li>
                <?php $i++;}?>
                <?php for($j=0; $j<(10-$i); $j++){?>
                    <li class="empty"></li>
                <?php }?>
            </ul>
        </div>
    </div>
    <div class="form-group">
        <label for="room_type" class="col-sm-2 control-label">房型</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="Room[room_type]" id="room_type" placeholder="房型" readonly value="<?php echo $room['room_type']?>">
        </div>
    </div>
    <div class="form-group">
        <label for="acreage" class="col-sm-2 control-label">面积</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="Room[acreage]" id="acreage" placeholder="面积" value="<?php echo $room['acreage']?>">
        </div>
    </div>
    <div class="form-group">
        <label for="floor" class="col-sm-2 control-label">楼层</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="Room[floor]" id="floor" placeholder="楼层" value="<?php echo $room['floor']?>">
        </div>
    </div>
    <div class="form-group">
        <label for="bed_type" class="col-sm-2 control-label">床型</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="Room[bed_type]" id="bed_type" placeholder="床型" value="<?php echo $room['bed_type']?>">
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-2 control-label">描述</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="Room[description]" id="description" rows="2"><?php echo $room['description']?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="desc" class="col-sm-2 control-label">内容</label>
        <div class="col-sm-9">
            <script class="editor" id="desc" name="Room[desc]" type="text/plain" style="height: 250px;"><?php echo $room['desc']?></script>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-10 col-md-offset-2">
            <button type="reset" class="btn btn-default btn-sm" data-dismiss="modal"><span class="glyphicon glyphicon-repeat"></span> 重置</button>
            <button type="button" class="btn btn-primary btn-sm" id="mod"><span class="glyphicon glyphicon-ok"></span> 提交</button>
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
    var editorObj = 'desc';
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

    /*修改*/
    $("#mod").click(function(){
        /*相册图片，循环出一个input列表>>>*/
            $('#upload').nextAll().remove();
            $('#upload').find('.full').each(function(){
                $('#upload').parent().append(`<input type="hidden" name="Room[album_img][]" value="`+ $(this).find('img').attr('src') +`">`);
            })
        /*相册图片，循环出一个input列表<<<*/
        jajax("<?php echo Url::to(['room/mod-room'])?>", $('#modForm').serialize());
    })
</script>
<!-- 编辑器按钮 -->
<script type="text/javascript" charset="utf-8" src="<?php echo ADMIN_SITE_URL;?>ueditor1_4_3_3/addCustomizeButton.js"></script>