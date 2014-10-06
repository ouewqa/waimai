<?php
/* @var $this WeixinQrcodeController */
/* @var $model WeixinQrcode */
?>

<?php
$this->breadcrumbs=array(
	'Weixin Qrcodes'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List WeixinQrcode', 'url'=>array('index')),
	array('label'=>'Create WeixinQrcode', 'url'=>array('create')),
	array('label'=>'View WeixinQrcode', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage WeixinQrcode', 'url'=>array('admin')),
);
?>

    <h1>Update WeixinQrcode <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>