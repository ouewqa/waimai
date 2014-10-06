<?php
/* @var $this WeixinCommandController */
/* @var $model WeixinCommand */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'weixin-command-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

    <?php echo $form->hiddenField($model, 'weixin_account_id'); ?>

	<div class="control-group">
		<?php echo $form->labelEx($model,'command'); ?>
		<?php echo $form->textField($model,'command',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'command'); ?>
	</div>

    <div class="control-group">
        <?php echo $form->labelEx($model,'match'); ?>
        <?php echo $form->dropDownList($model, 'match', OutputHelper::getWeixinCommandMatchTypeList()); ?>
        <?php echo $form->error($model,'match'); ?>
    </div>



    <div class="control-group">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>1024)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'type'); ?>
        <?php echo $form->dropDownList($model, 'type', OutputHelper::getWeixinCommandTypeList()); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>

    <div class="control-group">
        <?php echo $form->labelEx($model,'cost'); ?>
        <?php echo $form->textField($model,'cost',array('size'=>60,'maxlength'=>3)); ?>
        <?php echo $form->error($model,'cost'); ?>
    </div>

	<div class="control-group">
		<?php echo $form->labelEx($model,'status'); ?>
        <?php echo $form->dropDownList($model, 'status', OutputHelper::getStatusEnabledDisableList()); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="control-group buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->