<?php 
    use yii\helpers\Url;
?>
<nav class="navbar navbar-default child-nav">
	<h5 class="nav pull-left">推荐分类列表</h5>
	<div class="nav pull-right">
		<a href="<?php echo yii\helpers\Url::to(['article_category/add-category'])?>" type="button" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-plus"></span> 添加推荐分类</a>
	</div>
</nav>
<div class="table-responsive">
	<table class="table table-bordered table-hover table-condensed table-striped">
		<thead>
			<tr class="active">
				<th class="text-center width-50">ID</th>
				<th>推荐分类名称</th>
				<!-- <th>推荐数量</th> -->
				<th>描述</th>
				<th class="text-center width-150">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($categoryList as $k => $v){?>	
			<tr>
				<td class="text-center"><?php echo $v['cat_id'];?></td>
				<td><?php echo $v['cat_name'];?></td>
				<!-- <td><?php echo $v['article_number'];?></td> -->
				<td><?php echo $v['cat_desc'];?></td>
				<td class="text-center" data-id="<?php echo $v['cat_id'];?>">
					<button type="button" class="btn btn-danger btn-xs del"><span class="glyphicon glyphicon-remove"></span> 删除</button>
					<a href="<?php echo Url::to(['article_category/mod-category', 'id' => $v['cat_id']])?>" type="button" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-pencil"></span> 修改</a>
				</td>
			</tr>
			<?php }?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	/*删除分类*/
	confirmation($('.del'), function(){
		var self = $(".popover").prev();
		self.confirmation('hide');
		var id = self.parent().data("id");
		if(id){
			var data = {
				'id': id
			}
			jajax('<?php echo Url::to(['article_category/del-category'])?>', data);
		}
	});
</script>