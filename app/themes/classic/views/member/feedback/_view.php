<?php
/* @var $this FeedbackController */
/* @var $data Feedback */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('weixin_id')); ?>:</b>
	<?php echo CHtml::encode($data->weixin_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('weixin_account')); ?>:</b>
	<?php echo CHtml::encode($data->weixin_account); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('weixin')); ?>:</b>
	<?php echo CHtml::encode($data->weixin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mobile')); ?>:</b>
	<?php echo CHtml::encode($data->mobile); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('image')); ?>:</b>
	<?php echo CHtml::encode($data->image); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
	<?php echo CHtml::encode($data->content); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reply')); ?>:</b>
	<?php echo CHtml::encode($data->reply); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dateline')); ?>:</b>
	<?php echo CHtml::encode($data->dateline); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reply_time')); ?>:</b>
	<?php echo CHtml::encode($data->reply_time); ?>
	<br />

	*/ ?>

</div>