<?php
/* @var $this SiteHelpController */
/* @var $model SiteHelp */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'site-help-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => false,
    )); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldControlGroup($model, 'title', array('span' => 5, 'maxlength' => 45)); ?>

    <?php
    $this->widget('ext.ueditor.Ueditor', array(
            'model' => $model,
            'attribute' => 'content',
    ));
    ?>

    <?php
    if(!$model->isNewRecord){
        echo $form->dropDownListControlGroup($model, 'status', OutputHelper::getStatusEnabledDisableList(), array('span' => 5, 'maxlength' => 1));
    }
    ?>

    <div class="form-actions">
        <?php echo TbHtml::submitButton($model->isNewRecord ? '创建' : '保存', array(
                'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'size' => TbHtml::BUTTON_SIZE_LARGE,
        )); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->