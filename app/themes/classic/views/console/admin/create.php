<?php
/* @var $this AdminController */
/* @var $model Admin */
?>

<?php
$this->breadcrumbs=array(
	'Admins'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Admin', 'url'=>array('index')),
	array('label'=>'Manage Admin', 'url'=>array('admin')),
);
?>

<h1>Create Admin</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>