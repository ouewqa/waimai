<?php
/* @var $this WeixinMessageController */
/* @var $model WeixinMessage */

$this->breadcrumbs=array(
	'Weixin Messages'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List WeixinMessage', 'url'=>array('index')),
	array('label'=>'Create WeixinMessage', 'url'=>array('create')),
	array('label'=>'Update WeixinMessage', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WeixinMessage', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WeixinMessage', 'url'=>array('admin')),
);
?>

<h1>View WeixinMessage #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'from_user',
		'to_user',
		'message',
		'flag',
		'type',
		'dateline',
		'status',
	),
)); ?>
