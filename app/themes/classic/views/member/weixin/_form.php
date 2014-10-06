<?php
/* @var $this WeixinController */
/* @var $model Weixin */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'weixin-form',
            'enableAjaxValidation' => false,
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>
    <?php echo $form->hiddenField($model, 'weixin_account_id'); ?>

    <?php if ($this->account->advanced_interface == 'Y') : ?>
        <div class="control-group">
            <?php echo $form->labelEx($model, 'weixin_group_id'); ?>
            <?php echo $form->dropDownList($model, 'weixin_group_id', CHtml::listData(WeixinGroup::model()->getGroupList($this->account->id), 'group_id', 'name')); ?>
            <?php echo $form->error($model, 'weixin_group_id'); ?>
        </div>
    <?php endif; ?>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'nickname'); ?>
        <?php echo $form->textField($model, 'nickname', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'nickname'); ?>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'mobile'); ?>
        <?php echo $form->textField($model, 'mobile', array('size' => 11, 'maxlength' => 11)); ?>
        <?php echo $form->error($model, 'mobile'); ?>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'realname'); ?>
        <?php echo $form->textField($model, 'realname', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'realname'); ?>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'qq'); ?>
        <?php echo $form->textField($model, 'qq', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'qq'); ?>
    </div>


    <div class="control-group">
        <?php echo $form->labelEx($model, 'sex'); ?>
        <?php echo $form->dropDownList($model, 'sex', OutputHelper::getSexList()); ?>
        <?php echo $form->error($model, 'sex'); ?>
    </div>


    <div class="control-group">
        <?php echo $form->labelEx($model, 'birthday'); ?>
        <?php echo $form->textField($model, 'birthday'); ?>
        <?php echo $form->error($model, 'birthday'); ?>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'status'); ?>
        <?php echo $form->dropDownList($model, 'status', OutputHelper::getWeixinMemberStatuslist(), array('size' => 1)); ?>
        <?php echo $form->error($model, 'status'); ?>
    </div>


    <div class="control-group buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->