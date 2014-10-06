<?php
/* @var $this ShopOrderController */
/* @var $data ShopOrder */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('weixin_id')); ?>:</b>
	<?php echo CHtml::encode($data->weixin_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('delivery_time')); ?>:</b>
	<?php echo CHtml::encode($data->delivery_time); ?>
	<br />


	<b><?php echo CHtml::encode($data->getAttributeLabel('comment')); ?>:</b>
	<?php echo CHtml::encode($data->comment); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('payment_method_id')); ?>:</b>
	<?php echo CHtml::encode($data->payment_method_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />


</div>