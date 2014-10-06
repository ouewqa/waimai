<?php
/* @var $this WeixinMenuController */
/* @var $model WeixinMenu */

$this->breadcrumbs=array(
	'微信自定义菜单'=>array('index'),
	$model->name,
);
?>

<h1>更新自定义菜单</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>