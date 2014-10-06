<?php
/* @var $this WeixinGroupController */
/* @var $model WeixinGroup */

$this->breadcrumbs=array(
	'微信分组'=>array('index'),
	'创建',
);
?>

<h1>创建分组</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>