<?php
/* @var $this WeixinQrcodeController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs=array(
	'Weixin Qrcodes',
);

$this->menu=array(
	array('label'=>'Create WeixinQrcode','url'=>array('create')),
	array('label'=>'Manage WeixinQrcode','url'=>array('admin')),
);
?>

<h1>Weixin Qrcodes</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>