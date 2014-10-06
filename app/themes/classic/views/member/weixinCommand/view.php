<?php
/* @var $this WeixinCommandController */
/* @var $model WeixinCommand */

$this->breadcrumbs=array(
	'Weixin Commands'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List WeixinCommand', 'url'=>array('index')),
	array('label'=>'Create WeixinCommand', 'url'=>array('create')),
	array('label'=>'Update WeixinCommand', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WeixinCommand', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WeixinCommand', 'url'=>array('admin')),
);
?>

<h1>View WeixinCommand #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'source',
		'command',
		'description',
		'type',
		'value',
		'status',
	),
)); ?>
