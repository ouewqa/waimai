<?php
/* @var $this MaterialController */
/* @var $model Material */
/* @var $form TbActiveForm */
?>



<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'material-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
)); ?>

<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

<?php echo (isset($model->keyword) && in_array($model->keyword, array('defaultResponse', 'followResponse', 'lbsResponse'))) ? '' : $form->textFieldControlGroup($model, 'keyword', array('span' => 5, 'maxlength' => 45)); ?>
<?php echo $form->textAreaControlGroup($model, 'description', array('span' => 5, 'maxlength' => 45, 'rows' => '8')); ?>

<?php echo $form->hiddenField($model, 'type', array('value' => 'T')); ?>

<div class="form-actions">
    <?php echo TbHtml::submitButton($model->isNewRecord ? '创建' : '保存', array(
            'color' => TbHtml::BUTTON_COLOR_PRIMARY,
            'size' => TbHtml::BUTTON_SIZE_LARGE,
    )); ?>
</div>

<?php $this->endWidget(); ?>

