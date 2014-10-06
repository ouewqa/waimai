<?php
/* @var $this WeixinController */
/* @var $model Weixin */

$this->breadcrumbs=array(
	'Weixins'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Weixin', 'url'=>array('index')),
	array('label'=>'Create Weixin', 'url'=>array('create')),
	array('label'=>'Update Weixin', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Weixin', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Weixin', 'url'=>array('admin')),
);
?>

<h1>View Weixin #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'member_id',
		'nickname',
		'sex',
		'language',
		'country',
		'province',
		'city',
		'headimgurl',
		'open_id',
		'source',
		'status',
		'dateline',
	),
)); ?>
