<?php
/* @var $this KeywordController */
/* @var $model Keyword */
?>

<?php
$this->breadcrumbs=array(
	'Keywords'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Keyword', 'url'=>array('index')),
	array('label'=>'Create Keyword', 'url'=>array('create')),
	array('label'=>'View Keyword', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Keyword', 'url'=>array('admin')),
);
?>

    <h1>Update Keyword <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>