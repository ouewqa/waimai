<?php
/* @var $this SiteProductCategoryController */
/* @var $model SiteProductCategory */
?>

<?php
$this->breadcrumbs=array(
	'Site Product Categories'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List SiteProductCategory', 'url'=>array('index')),
	array('label'=>'Create SiteProductCategory', 'url'=>array('create')),
	array('label'=>'Update SiteProductCategory', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SiteProductCategory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SiteProductCategory', 'url'=>array('admin')),
);
?>

<h1>View SiteProductCategory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'name',
		'ob',
		'status',
	),
)); ?>