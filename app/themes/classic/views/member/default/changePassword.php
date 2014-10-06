<?php
$this->breadcrumbs = array(
        '修改密码',
);
?>
<div class="form">

    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'admin-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => false,
    )); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php if (Yii::app()->user->hasFlash('changePaswordStatus')): ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo Yii::app()->user->getFlash('changePaswordStatus'); ?>
        </div>

        <?php
        Yii::app()->clientScript->registerScript(
                'myHideEffect',
                '$(".alert").animate({opacity: 1.0}, 3000).fadeOut("slow");',
                CClientScript::POS_READY
        );
        ?>
    <?php endif; ?>


    <?php echo $form->passwordFieldControlGroup($model, 'oldPassword', array('span' => 5, 'maxlength' => 45)); ?>

    <?php echo $form->passwordFieldControlGroup($model, 'newPassword', array('span' => 5, 'maxlength' => 32)); ?>

    <?php echo $form->passwordFieldControlGroup($model, 'repeatPassword', array('span' => 5, 'maxlength' => 11)); ?>


    <div class="form-actions">
        <?php echo TbHtml::submitButton('保存', array(
                'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'size' => TbHtml::BUTTON_SIZE_LARGE,
        )); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->