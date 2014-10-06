<?php
/* @var $this SiteProductCategoryController */
/* @var $model SiteProductCategory */
?>

<?php
$this->breadcrumbs=array(
	'Site Product Categories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SiteProductCategory', 'url'=>array('index')),
	array('label'=>'Manage SiteProductCategory', 'url'=>array('admin')),
);
?>

<h1>Create SiteProductCategory</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>