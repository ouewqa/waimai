<?php
/* @var $this WeixinAccountController */
/* @var $data WeixinAccount */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('admin_id')); ?>:</b>
	<?php echo CHtml::encode($data->admin_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('source')); ?>:</b>
	<?php echo CHtml::encode($data->source); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('welcome_message')); ?>:</b>
	<?php echo CHtml::encode($data->welcome_message); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('appid')); ?>:</b>
	<?php echo CHtml::encode($data->appid); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('appsecret')); ?>:</b>
	<?php echo CHtml::encode($data->appsecret); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('token')); ?>:</b>
	<?php echo CHtml::encode($data->token); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('access_token')); ?>:</b>
	<?php echo CHtml::encode($data->access_token); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('access_token_expire_time')); ?>:</b>
	<?php echo CHtml::encode($data->access_token_expire_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('baidu_ak')); ?>:</b>
	<?php echo CHtml::encode($data->baidu_ak); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('advanced_interface')); ?>:</b>
	<?php echo CHtml::encode($data->advanced_interface); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('default')); ?>:</b>
	<?php echo CHtml::encode($data->default); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('debug')); ?>:</b>
	<?php echo CHtml::encode($data->debug); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	*/ ?>

</div>