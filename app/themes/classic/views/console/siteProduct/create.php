<?php
/* @var $this SiteProductController */
/* @var $model SiteProduct */
?>

<?php
$this->breadcrumbs=array(
	'Site Products'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SiteProduct', 'url'=>array('index')),
	array('label'=>'Manage SiteProduct', 'url'=>array('admin')),
);
?>

<h1>Create SiteProduct</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>