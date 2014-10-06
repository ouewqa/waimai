<?php
/* @var $this WeixinMenuController */
/* @var $model WeixinMenu */

$this->breadcrumbs=array(
	'微信自定义菜单'=>array('index'),
	'创建',
);
?>

<h1>创建菜单</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>