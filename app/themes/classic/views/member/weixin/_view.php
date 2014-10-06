<?php
/* @var $this WeixinController */
/* @var $data Weixin */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('weixin_account_id')); ?>:</b>
	<?php echo CHtml::encode($data->weixin_account_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('member_id')); ?>:</b>
	<?php echo CHtml::encode($data->member_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('open_id')); ?>:</b>
	<?php echo CHtml::encode($data->open_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('source')); ?>:</b>
	<?php echo CHtml::encode($data->source); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('weixin_group_id')); ?>:</b>
	<?php echo CHtml::encode($data->weixin_group_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nickname')); ?>:</b>
	<?php echo CHtml::encode($data->nickname); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('birthday')); ?>:</b>
	<?php echo CHtml::encode($data->birthday); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sex')); ?>:</b>
	<?php echo CHtml::encode($data->sex); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('language')); ?>:</b>
	<?php echo CHtml::encode($data->language); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('country')); ?>:</b>
	<?php echo CHtml::encode($data->country); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('province')); ?>:</b>
	<?php echo CHtml::encode($data->province); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('city')); ?>:</b>
	<?php echo CHtml::encode($data->city); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('headimgurl')); ?>:</b>
	<?php echo CHtml::encode($data->headimgurl); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mobile')); ?>:</b>
	<?php echo CHtml::encode($data->mobile); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('realname')); ?>:</b>
	<?php echo CHtml::encode($data->realname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qq')); ?>:</b>
	<?php echo CHtml::encode($data->qq); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('role')); ?>:</b>
	<?php echo CHtml::encode($data->role); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('credit')); ?>:</b>
	<?php echo CHtml::encode($data->credit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('coin')); ?>:</b>
	<?php echo CHtml::encode($data->coin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('jp_level')); ?>:</b>
	<?php echo CHtml::encode($data->jp_level); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nhk_time')); ?>:</b>
	<?php echo CHtml::encode($data->nhk_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nhk_mode')); ?>:</b>
	<?php echo CHtml::encode($data->nhk_mode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('opportunity_mode')); ?>:</b>
	<?php echo CHtml::encode($data->opportunity_mode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nhk_news_number')); ?>:</b>
	<?php echo CHtml::encode($data->nhk_news_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('translation_mode')); ?>:</b>
	<?php echo CHtml::encode($data->translation_mode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('identity')); ?>:</b>
	<?php echo CHtml::encode($data->identity); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('subscribe_keyword')); ?>:</b>
	<?php echo CHtml::encode($data->subscribe_keyword); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('auto_translation')); ?>:</b>
	<?php echo CHtml::encode($data->auto_translation); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('invitation_count')); ?>:</b>
	<?php echo CHtml::encode($data->invitation_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dateline')); ?>:</b>
	<?php echo CHtml::encode($data->dateline); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updatetime')); ?>:</b>
	<?php echo CHtml::encode($data->updatetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('variety_cate')); ?>:</b>
	<?php echo CHtml::encode($data->variety_cate); ?>
	<br />

	*/ ?>

</div>