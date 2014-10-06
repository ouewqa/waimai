<?php
/* @var $this WeixinGroupController */
/* @var $model WeixinGroup */

$this->breadcrumbs=array(
	'Weixin Groups'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List WeixinGroup', 'url'=>array('index')),
	array('label'=>'Create WeixinGroup', 'url'=>array('create')),
	array('label'=>'Update WeixinGroup', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WeixinGroup', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WeixinGroup', 'url'=>array('admin')),
);
?>

<h1>View WeixinGroup #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
