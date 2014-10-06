<?php
/* @var $this WeixinQrcodeController */
/* @var $model WeixinQrcode */
?>

<?php
$this->breadcrumbs=array(
	'Weixin Qrcodes'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List WeixinQrcode', 'url'=>array('index')),
	array('label'=>'Create WeixinQrcode', 'url'=>array('create')),
	array('label'=>'Update WeixinQrcode', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WeixinQrcode', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WeixinQrcode', 'url'=>array('admin')),
);
?>

<h1>View WeixinQrcode #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'weixin_account_id',
		'scene_id',
		'ticket',
		'path',
		'type',
		'description',
		'scan_count',
		'dateline',
	),
)); ?>