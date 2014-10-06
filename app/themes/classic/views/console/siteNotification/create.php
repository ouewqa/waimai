<?php
/* @var $this SiteNotificationController */
/* @var $model SiteNotification */
?>

<?php
$this->breadcrumbs=array(
	'网站通知'=>array('index'),
	'创建',
);
?>

<h1>创建网站通知</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>