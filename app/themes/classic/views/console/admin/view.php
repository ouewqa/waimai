<?php
/* @var $this AdminController */
/* @var $model Admin */
?>

<?php
$this->breadcrumbs=array(
	'Admins'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Admin', 'url'=>array('index')),
	array('label'=>'Create Admin', 'url'=>array('create')),
	array('label'=>'Update Admin', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Admin', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Admin', 'url'=>array('admin')),
);
?>

<h1>View Admin #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'role',
		'username',
		'password',
		'mobile',
		'email',
		'mobile_is_verify',
		'email_is_verify',
		'count_sms',
		'count_email',
		'regist_ip',
		'regist_time',
		'last_login_time',
		'last_login_ip',
		'login_times',
		'salt',
		'expire',
		'status',
	),
)); ?>