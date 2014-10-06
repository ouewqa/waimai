<?php
/* @var $this WeixinCommandController */
/* @var $model WeixinCommand */

$this->breadcrumbs=array(
	'Weixin Commands'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List WeixinCommand', 'url'=>array('index')),
	array('label'=>'Create WeixinCommand', 'url'=>array('create')),
	array('label'=>'View WeixinCommand', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage WeixinCommand', 'url'=>array('admin')),
);
?>

<h1>Update WeixinCommand <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>