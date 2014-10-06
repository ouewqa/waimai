<?php
/* @var $this FeedbackController */
/* @var $model Feedback */
?>

<?php
$this->breadcrumbs=array(
	'意见反馈'=>array('index'),
	'管理员回复',
);

$this->menu=array(
	array('label'=>'List Feedback', 'url'=>array('index')),
	array('label'=>'Create Feedback', 'url'=>array('create')),
	array('label'=>'View Feedback', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Feedback', 'url'=>array('admin')),
);
?>

    <h1>管理员回复</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>