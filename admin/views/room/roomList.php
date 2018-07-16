<?php 
    use yii\helpers\Url;
?>
<nav class="navbar navbar-default child-nav">
	<h5 class="nav pull-left">客房列表</h5>
	<div class="nav pull-right">
		<span id="synchronous" type="button" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-import"></span> 同步接口数据</span>
	</div>
</nav>
<div class="table-responsive">
	<table class="table table-bordered table-hover table-condensed table-striped">
		<thead>
			<tr class="active">
				<th class="text-center width-50">ID</th>
				<th>客房名称</th>
				<th>类型</th>
				<th>面积</th>
				<th>楼层</th>
				<th>床型</th>
				<th class="text-center width-150">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($roomList as $k => $v){?>	
			<tr>
				<td class="text-center"><?php echo $v['room_id']?></td>
				<td><?php echo $v['room_name']?></td>
				<td><?php echo $v['room_type']?></td>
				<td><?php echo $v['acreage']?></td>
				<td><?php echo $v['floor']?></td>
				<td><?php echo $v['bed_type']?></td>
				<td class="text-center" data-id="<?php echo $v['room_id'];?>">
					<a href="<?php echo Url::to(['room/mod-room', 'room_id' => $v['room_id']])?>" type="button" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-pencil"></span> 修改</a>
				</td>
			</tr>
			<?php }?>
		</tbody>
		<tfoot class="pages">
			<tr>
				<td class="pagelist noselect text-right" colspan="11"></td>
			</tr>
		</tfoot>
	</table>
</div>
<script type="text/javascript">
	/*同步*/
	$("#synchronous").click(function(){
		jajax("<?php echo yii\helpers\Url::to(['room/synchronous'])?>");
	})
	
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
			window.location.href = "<?php echo Url::to(['room/room-list'])?>?page=" + currPage;
		},
		callback: function (currPage, size, count) {
			// jajax("<?php echo Url::to(['room/room-list'])?>?page=" + currPage);
			window.location.href = "<?php echo Url::to(['room/room-list'])?>?page=" + currPage;
		}
	});
</script>