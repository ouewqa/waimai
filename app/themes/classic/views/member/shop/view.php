<?php
/* @var $this ShopController */
/* @var $model Shop */
?>

<?php
$this->breadcrumbs=array(
	'Shops'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Shop', 'url'=>array('index')),
	array('label'=>'Create Shop', 'url'=>array('create')),
	array('label'=>'Update Shop', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Shop', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Shop', 'url'=>array('admin')),
);
?>

<h1>View Shop #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'weixin_account_id',
		'name',
		'sn',
		'description',
		'province',
		'city',
		'district',
		'address',
		'telephone',
		'mobile',
		'map_point',
		'opening_time_start',
		'opening_time_end',
		'minimum_charge',
		'express_fee',
		'status',
		'dateline',
	),
)); ?>