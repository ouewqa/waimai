<?php
/* @var $this SiteNotificationController */
/* @var $model SiteNotification */
?>

<?php
$this->breadcrumbs=array(
	'Site Notifications'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List SiteNotification', 'url'=>array('index')),
	array('label'=>'Create SiteNotification', 'url'=>array('create')),
	array('label'=>'Update SiteNotification', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SiteNotification', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SiteNotification', 'url'=>array('admin')),
);
?>

<h1>View SiteNotification #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'title',
		'content',
		'dateline',
		'expire',
	),
)); ?>