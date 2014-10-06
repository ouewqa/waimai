<?php
/* @var $this WeixinMessageController */
/* @var $model WeixinMessage */

$this->breadcrumbs=array(
	'Weixin Messages'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List WeixinMessage', 'url'=>array('index')),
	array('label'=>'Create WeixinMessage', 'url'=>array('create')),
	array('label'=>'View WeixinMessage', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage WeixinMessage', 'url'=>array('admin')),
);
?>

<h1>Update WeixinMessage <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>