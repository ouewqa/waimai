<?php
/* @var $this AdminController */
/* @var $data Admin */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('role')); ?>:</b>
	<?php echo CHtml::encode($data->role); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mobile')); ?>:</b>
	<?php echo CHtml::encode($data->mobile); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mobile_is_verify')); ?>:</b>
	<?php echo CHtml::encode($data->mobile_is_verify); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('email_is_verify')); ?>:</b>
	<?php echo CHtml::encode($data->email_is_verify); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('count_sms')); ?>:</b>
	<?php echo CHtml::encode($data->count_sms); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('count_email')); ?>:</b>
	<?php echo CHtml::encode($data->count_email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('regist_ip')); ?>:</b>
	<?php echo CHtml::encode($data->regist_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('regist_time')); ?>:</b>
	<?php echo CHtml::encode($data->regist_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_login_time')); ?>:</b>
	<?php echo CHtml::encode($data->last_login_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_login_ip')); ?>:</b>
	<?php echo CHtml::encode($data->last_login_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('login_times')); ?>:</b>
	<?php echo CHtml::encode($data->login_times); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('salt')); ?>:</b>
	<?php echo CHtml::encode($data->salt); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('expire')); ?>:</b>
	<?php echo CHtml::encode($data->expire); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	*/ ?>

</div>