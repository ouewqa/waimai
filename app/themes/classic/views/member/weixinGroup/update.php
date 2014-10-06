<?php
/* @var $this WeixinGroupController */
/* @var $model WeixinGroup */

$this->breadcrumbs=array(
	'微信分组'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'更新',
);
?>

<h1>更新分组 <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>