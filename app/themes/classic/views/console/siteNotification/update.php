<?php
/* @var $this SiteNotificationController */
/* @var $model SiteNotification */
?>

<?php
$this->breadcrumbs=array(
	'网站通知'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List SiteNotification', 'url'=>array('index')),
	array('label'=>'Create SiteNotification', 'url'=>array('create')),
	array('label'=>'View SiteNotification', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SiteNotification', 'url'=>array('admin')),
);
?>

    <h1>更新通知</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>