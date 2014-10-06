<?php
/* @var $this WeixinController */
/* @var $model Weixin */

$this->breadcrumbs=array(
	'Weixins'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Weixin', 'url'=>array('index')),
	array('label'=>'Manage Weixin', 'url'=>array('admin')),
);
?>

<h1>Create Weixin</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>