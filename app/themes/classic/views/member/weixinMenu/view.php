<?php
/* @var $this WeixinMenuController */
/* @var $model WeixinMenu */

$this->breadcrumbs=array(
	'Weixin Menus'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List WeixinMenu', 'url'=>array('index')),
	array('label'=>'Create WeixinMenu', 'url'=>array('create')),
	array('label'=>'Update WeixinMenu', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WeixinMenu', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WeixinMenu', 'url'=>array('admin')),
);
?>

<h1>View WeixinMenu #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'fid',
		'name',
		'type',
		'url',
		'ob',
	),
)); ?>
