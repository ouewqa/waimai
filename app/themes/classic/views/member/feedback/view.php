<?php
/* @var $this FeedbackController */
/* @var $model Feedback */
?>

<?php
$this->breadcrumbs=array(
	'Feedbacks'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Feedback', 'url'=>array('index')),
	array('label'=>'Create Feedback', 'url'=>array('create')),
	array('label'=>'Update Feedback', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Feedback', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Feedback', 'url'=>array('admin')),
);
?>

<h1>View Feedback #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'weixin_id',
		'weixin_account',
		'weixin',
		'mobile',
		'email',
		'image',
		'content',
		'reply',
		'dateline',
		'reply_time',
	),
)); ?>