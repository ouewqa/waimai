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
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

<?php echo (isset($model->keyword) && in_array($model->keyword, array('defaultResponse', 'followResponse', 'lbsResponse'))) ? '' : $form->textFieldControlGroup($model, 'keyword', array('span' => 5, 'maxlength' => 45)); ?>

<?php echo $form->textFieldControlGroup($model, 'title', array('span' => 5, 'maxlength' => 45)); ?>

<div class="control-group">
    <?php echo $form->labelEx($model, 'image'); ?>

    <?php if (!$model->isNewRecord && $model->image) { ?>
        <p>
            <?php echo CHtml::image($model->image, '', array('style' => 'max-width:300px;')); ?>
        </p>
        <p>
            <input type="hidden" name="image2" class="span12" value="<?php echo $model->image; ?>" />
        </p>

    <?php } ?>
    <?php echo $form->error($model, 'image'); ?>
</div>
<div class="control-group">
    <?php echo CHtml::activeFileField($model, 'image', array('value' => $model->image)); ?>
</div>


<?php echo $form->textAreaControlGroup($model, 'description', array('span' => 5, 'maxlength' => 1024, 'rows' => '6')); ?>

<?php
/*$this->widget('ext.ueditor.Ueditor', array(
        'model' => $model,
        'attribute' => 'content',
        'width' => '98%'
));
*/?>

<?php echo $form->hiddenField($model, 'type', array('value' => 'N')); ?>

<?php echo $form->dropDownListControlGroup($model, 'url', OutputHelper::getMaterialUrlList(), array('span' => 5, 'maxlength' => 1024, 'rows' => '3')); ?>




<div class="form-actions">
    <?php echo TbHtml::submitButton($model->isNewRecord ? '创建' : '保存', array(
            'color' => TbHtml::BUTTON_COLOR_PRIMARY,
            'size' => TbHtml::BUTTON_SIZE_LARGE,
    )); ?>
</div>

<?php $this->endWidget(); ?>
