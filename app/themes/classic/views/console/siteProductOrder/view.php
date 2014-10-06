<?php
/* @var $this SiteProductOrderController */
/* @var $model SiteProductOrder */
?>

<?php
$this->breadcrumbs=array(
	'Site Product Orders'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List SiteProductOrder', 'url'=>array('index')),
	array('label'=>'Create SiteProductOrder', 'url'=>array('create')),
	array('label'=>'Update SiteProductOrder', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SiteProductOrder', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SiteProductOrder', 'url'=>array('admin')),
);
?>

<h1>View SiteProductOrder #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'payment',
		'order_sn',
		'admin_id',
		'buyer',
		'site_product_id',
		'subject',
		'description',
		'price',
		'number',
		'money',
		'url',
		'dateline',
		'status',
	),
)); ?>