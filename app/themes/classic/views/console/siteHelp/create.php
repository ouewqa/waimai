<?php
/* @var $this SiteHelpController */
/* @var $model SiteHelp */
?>

<?php
$this->breadcrumbs=array(
	'网站帮助'=>array('index'),
	'创建',
);
?>

<h1>创建网站帮助</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>