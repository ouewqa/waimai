<?php
/* @var $this AnnouncementController */
/* @var $model Announcement */
?>

<?php
$this->breadcrumbs=array(
	'门店公告'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'更新',
);
?>

    <h1>更新公告</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>