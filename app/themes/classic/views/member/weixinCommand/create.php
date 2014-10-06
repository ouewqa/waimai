<?php
/* @var $this WeixinCommandController */
/* @var $model WeixinCommand */

$this->breadcrumbs=array(
	'Weixin Commands'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List WeixinCommand', 'url'=>array('index')),
	array('label'=>'Manage WeixinCommand', 'url'=>array('admin')),
);
?>

<h1>Create WeixinCommand</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>