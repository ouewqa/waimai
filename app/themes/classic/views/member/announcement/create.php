<?php
/* @var $this AnnouncementController */
/* @var $model Announcement */
?>

<?php
$this->breadcrumbs=array(
	'门店公告'=>array('index'),
	'添加',
);

$this->menu=array(
	array('label'=>'List Announcement', 'url'=>array('index')),
	array('label'=>'Manage Announcement', 'url'=>array('admin')),
);
?>

<h1>添加公告</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>