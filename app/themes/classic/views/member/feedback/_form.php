<?php
/* @var $this FeedbackController */
/* @var $model Feedback */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'feedback-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => false,
    )); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldControlGroup($model, 'weixin_account', array('span' => 5, 'maxlength' => 45, 'readonly' => 'readonly')); ?>

    <?php echo $form->textFieldControlGroup($model, 'mobile', array('span' => 5, 'maxlength' => 11, 'readonly' => 'readonly')); ?>

    <?php echo $form->textFieldControlGroup($model, 'email', array('span' => 5, 'maxlength' => 45, 'readonly' => 'readonly')); ?>

    <?php echo $form->textAreaControlGroup($model, 'content', array('span' => 5, 'maxlength' => 1024, 'readonly' => 'readonly', 'rows' => 5)); ?>

    <?php echo $form->textAreaControlGroup($model, 'reply', array('span' => 5, 'maxlength' => 1024, 'rows' => 5)); ?>

    <div class="form-actions">
        <?php echo TbHtml::submitButton($model->isNewRecord ? '创建' : '保存', array(
                'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'size' => TbHtml::BUTTON_SIZE_LARGE,
        )); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->