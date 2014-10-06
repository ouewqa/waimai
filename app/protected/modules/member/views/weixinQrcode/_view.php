<?php
/* @var $this WeixinQrcodeController */
/* @var $data WeixinQrcode */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('weixin_account_id')); ?>:</b>
	<?php echo CHtml::encode($data->weixin_account_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('scene_id')); ?>:</b>
	<?php echo CHtml::encode($data->scene_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ticket')); ?>:</b>
	<?php echo CHtml::encode($data->ticket); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('path')); ?>:</b>
	<?php echo CHtml::encode($data->path); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('scan_count')); ?>:</b>
	<?php echo CHtml::encode($data->scan_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dateline')); ?>:</b>
	<?php echo CHtml::encode($data->dateline); ?>
	<br />

	*/ ?>

</div>