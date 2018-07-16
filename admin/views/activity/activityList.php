<?php 
    use yii\helpers\Url;
?>
<nav class="navbar navbar-default child-nav">
	<h5 class="nav pull-left">活动列表</h5>
	<div class="nav pull-right">
		<a href="<?php echo yii\helpers\Url::to(['activity/add-activity'])?>" type="button" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-plus"></span> 添加活动</a>
	</div>
</nav>
<div class="table-responsive">
	<table class="table table-bordered table-hover table-condensed table-striped">
		<thead>
			<tr class="active">
				<th class="text-center width-50">ID</th>
				<th>活动标题</th>
				<th>演出时间</th>
				<th>最后修改时间</th>
				<th>创建时间</th>
				<th class="text-center width-150">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($activityList as $k => $v){?>	
			<tr>
				<td class="text-center"><?php echo $v['id']?></td>
				<td><?php echo $v['title']?></td>
				<td><?php echo $v['performance_time']?></td>
				<td><?php if($v['last_modify_time']){?><?php echo date('Y-m-d H:i:s', $v['last_modify_time']); ?><?php }?></td>
				<td><?php echo date('Y-m-d H:i:s', $v['add_time'])?></td>
				<td class="text-center" data-id="<?php echo $v['id'];?>">
					<button type="button" class="btn btn-danger btn-xs del"><span class="glyphicon glyphicon-remove"></span> 删除</button>
					<a href="<?php echo Url::to(['activity/mod-activity', 'id' => $v['id']])?>" type="button" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-pencil"></span> 修改</a>
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
	/*删除活动*/
	confirmation($('.del'), function(){
		var self = $(".popover").prev();
		self.confirmation('hide');
		var id = self.parent().data("id");
		if(id){
			var data = {
				'id': id
			}
			jajax('<?php echo Url::to(['activity/del-activity'])?>', data);
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
			window.location.href = "<?php echo Url::to(['activity/activity-list'])?>?page=" + currPage;
		},
		callback: function (currPage, size, count) {
			// jajax("<?php echo Url::to(['activity/activity-list'])?>?page=" + currPage);
			window.location.href = "<?php echo Url::to(['activity/activity-list'])?>?page=" + currPage;
		}
	});
</script>