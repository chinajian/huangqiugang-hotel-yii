<?php 
    use yii\helpers\Url;
?>
<nav class="navbar navbar-default child-nav">
	<h5 class="nav pull-left">商品列表</h5>
	<div class="nav pull-right">
		<a href="<?php echo yii\helpers\Url::to(['goods/add-goods'])?>" type="button" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-plus"></span> 添加商品</a>
	</div>
</nav>
<div class="table-responsive">
	<table class="table table-bordered table-hover table-condensed table-striped">
		<thead>
			<tr class="active">
				<th>预览</th>
				<th>标题[货号]</th>
				<th>精品</th>
				<th>新品</th>
				<th>热销</th>
				<th class="width-150">创建时间</th>
				<th class="text-center width-150">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($goodsList as $k => $v){?>
			<tr>
				<td><img src='<?php echo explode(',', $v['album_img'])[0];?>' style='max-width: 20px; max-height: 20px;'></td>
				<td><?php echo $v['goods_name'].' ['.$v['goods_sn'].']';?></td>
				<td><span class="glyphicon glyphicon-<?php echo ($v['is_best']==1)?'remove text-danger':'ok text-success';?> cursor"></span></td>
				<td><span class="glyphicon glyphicon-<?php echo ($v['is_new']==1)?'remove text-danger':'ok text-success';?> cursor"></span></td>
				<td><span class="glyphicon glyphicon-<?php echo ($v['is_hot']==1)?'remove text-danger':'ok text-success';?> cursor"></span></td>
				<td><?php echo date('Y-m-d H:i:s', $v['add_time']);?></td>
				<td class="text-center" data-id="<?php echo $v['goods_id'];?>">
					<button type="button" class="btn btn-danger btn-xs del"><span class="glyphicon glyphicon-remove"></span> 删除</button>
					<a href="<?php echo Url::to(['goods/mod-goods', 'id' => $v['goods_id']])?>" type="button" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-pencil"></span> 修改</a>
				</td>
			</tr>
			<?php }?>
		</tbody>
		<tfoot class="pages">
			<tr>
				<td class="pagelist noselect text-right" colspan="8"></td>
			</tr>
		</tfoot>
	</table>
</div>
<script type="text/javascript">
	/*删除商品*/
	confirmation($('.del'), function(){
		var self = $(".popover").prev();
		self.confirmation('hide');
		var id = self.parent().data("id");
		if(id){
			var data = {
				'id': id
			}
			jajax('<?php echo Url::to(['goods/del-goods'])?>', data);
		}
	});

	/*分页*/
	var page = new Paging();
	page.init({
		target: $('.pagelist'),
		pagesize: <?php echo $pageInfo['pageSize'];?>,
		count: <?php echo $pageInfo['count']?>,
		// toolbar: true,
		hash: true,
		current: <?php echo $pageInfo['currPage']?>,
		pageSizeList: [5, 10, 15, 20 ,50],
		changePagesize: function(currPage){
			window.location.href = "<?php echo Url::to(['goods/goods-list'])?>?page=" + currPage;
		},
		callback: function (currPage, size, count) {
			// jajax("<?php echo Url::to(['goods/goods-list'])?>&page=" + currPage);
			window.location.href = "<?php echo Url::to(['goods/goods-list'])?>?page=" + currPage;
		}
	});
</script>