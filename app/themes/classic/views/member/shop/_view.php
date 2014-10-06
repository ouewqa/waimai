<?php
/* @var $this ShopController */
/* @var $data Shop */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('weixin_account_id')); ?>:</b>
	<?php echo CHtml::encode($data->weixin_account_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sn')); ?>:</b>
	<?php echo CHtml::encode($data->sn); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('province')); ?>:</b>
	<?php echo CHtml::encode($data->province); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('city')); ?>:</b>
	<?php echo CHtml::encode($data->city); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('district')); ?>:</b>
	<?php echo CHtml::encode($data->district); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b>
	<?php echo CHtml::encode($data->address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('telephone')); ?>:</b>
	<?php echo CHtml::encode($data->telephone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mobile')); ?>:</b>
	<?php echo CHtml::encode($data->mobile); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('map_point')); ?>:</b>
	<?php echo CHtml::encode($data->map_point); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('opening_time_start')); ?>:</b>
	<?php echo CHtml::encode($data->opening_time_start); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('opening_time_end')); ?>:</b>
	<?php echo CHtml::encode($data->opening_time_end); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('minimum_charge')); ?>:</b>
	<?php echo CHtml::encode($data->minimum_charge); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('express_fee')); ?>:</b>
	<?php echo CHtml::encode($data->express_fee); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dateline')); ?>:</b>
	<?php echo CHtml::encode($data->dateline); ?>
	<br />

	*/ ?>

</div>