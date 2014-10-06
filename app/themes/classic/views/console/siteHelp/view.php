<?php
/* @var $this SiteHelpController */
/* @var $model SiteHelp */
?>

<?php
$this->breadcrumbs=array(
	'Site Helps'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List SiteHelp', 'url'=>array('index')),
	array('label'=>'Create SiteHelp', 'url'=>array('create')),
	array('label'=>'Update SiteHelp', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SiteHelp', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SiteHelp', 'url'=>array('admin')),
);
?>

<h1>View SiteHelp #<?php echo $model->id; ?></h1>

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
		'status',
	),
)); ?>