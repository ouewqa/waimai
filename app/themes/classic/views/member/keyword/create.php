<?php
/* @var $this KeywordController */
/* @var $model Keyword */
?>

<?php
$this->breadcrumbs=array(
	'Keywords'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Keyword', 'url'=>array('index')),
	array('label'=>'Manage Keyword', 'url'=>array('admin')),
);
?>

<h1>Create Keyword</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>