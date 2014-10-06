<?php
/* @var $this KeywordController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs=array(
	'Keywords',
);

$this->menu=array(
	array('label'=>'Create Keyword','url'=>array('create')),
	array('label'=>'Manage Keyword','url'=>array('admin')),
);
?>

<h1>Keywords</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>