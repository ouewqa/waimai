<?php
/* @var $this AnnouncementController */
/* @var $model Announcement */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'announcement-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => false,
    )); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>
    <?php
    $this->widget('ext.ueditor.Ueditor', array(
            'model' => $model,
            'attribute' => 'content',
    ));
    ?>

    <div class="control-group">
        <label class="control-label" for="Announcement_dateline">公告开始时间</label>

        <div class="controls">
            <div class="input-append">
                <?php $this->widget('yiiwheels.widgets.datepicker.WhDatePicker', array(
                        'name' => 'Announcement[dateline]',
                        'value' => $model->dateline ? date('Y-m-d', $model->dateline) : '',
                        'pluginOptions' => array(
                                'format' => 'yyyy-mm-dd',
                                'language' => 'zh-CN',
                        )
                ));
                ?>
                <span class="add-on"><icon class="icon-calendar"></icon></span>
            </div>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="Announcement_expire">公告过期时间</label>

        <div class="controls">
            <div class="input-append">
                <?php $this->widget('yiiwheels.widgets.datepicker.WhDatePicker', array(
                        'name' => 'Announcement[expire]',
                        'value' => $model->expire ? date('Y-m-d', $model->expire) : '',
                        'pluginOptions' => array(
                                'format' => 'yyyy-mm-dd',
                                'language' => 'zh-CN',
                        )
                ));
                ?>
                <span class="add-on"><icon class="icon-calendar"></icon></span>
            </div>
        </div>
    </div>


    <div class="form-actions">
        <?php echo TbHtml::submitButton($model->isNewRecord ? '创建' : '保存', array(
                'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'size' => TbHtml::BUTTON_SIZE_LARGE,
        )); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->