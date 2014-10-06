<?php
/* @var $this SiteProductController */
/* @var $model SiteProduct */
?>

<?php
$this->breadcrumbs=array(
	'Site Products'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List SiteProduct', 'url'=>array('index')),
	array('label'=>'Create SiteProduct', 'url'=>array('create')),
	array('label'=>'Update SiteProduct', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SiteProduct', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SiteProduct', 'url'=>array('admin')),
);
?>

<h1>View SiteProduct #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'site_product_category_id',
		'name',
		'description',
		'image',
		'content',
		'dateline',
	),
)); ?>