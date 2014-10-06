<?php
/* @var $this WeixinAccountController */
/* @var $model WeixinAccount */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'weixin-account-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => false,
    )); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldControlGroup($model, 'name', array('span' => 5, 'maxlength' => 45)); ?>

    <?php echo $form->textFieldControlGroup($model, 'source', array('span' => 5, 'maxlength' => 45)); ?>

    <?php echo $form->textFieldControlGroup($model, 'appid', array('span' => 5, 'maxlength' => 18)); ?>

    <?php echo $form->textFieldControlGroup($model, 'appsecret', array('span' => 5, 'maxlength' => 32)); ?>

    <?php
    if (!$model->isNewRecord) {
        $form->textFieldControlGroup($model, 'baidu_ak', array('span' => 5, 'maxlength' => 45));
    }
    ?>


    <?php echo $form->dropDownListControlGroup(
            $model,
            'type',
            OutputHelper::getWeixinAccountTypeList(),
            array('span' => 5, 'maxlength' => 1));
    ?>
    <?php echo $form->dropDownListControlGroup(
            $model,
            'advanced_interface',
            OutputHelper::getWeixinAdvancedInterfaceList(),
            array('span' => 5, 'maxlength' => 1));
    ?>


    <?php
    /*    if (!$model->isNewRecord) {
            echo $form->dropDownListControlGroup(
                    $model,
                    'default',
                    OutputHelper::getDefaultStatusList(),
                    array('span' => 5, 'maxlength' => 1));
        };
        */
    ?>


    <?php
    if (!$model->isNewRecord) {

        echo $form->dropDownListControlGroup(
                $model,
                'need_mobile_verify',
                OutputHelper::getNeedTypeList(),
                array('span' => 5, 'maxlength' => 1));


        echo $form->dropDownListControlGroup(
                $model,
                'notify_customer_method',
                OutputHelper::getNotifyCustomerMethodList(),
                array('span' => 5, 'maxlength' => 1));

    };
    ?>


    <div class="form-actions">
        <?php echo TbHtml::submitButton($model->isNewRecord ? '创建' : '保存', array(
                'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'size' => TbHtml::BUTTON_SIZE_LARGE,
        )); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->