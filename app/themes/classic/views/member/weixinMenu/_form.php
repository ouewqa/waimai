<?php
/* @var $this WeixinMenuController */
/* @var $model WeixinMenu */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'weixin-menu-form',
            'enableAjaxValidation' => false,
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->hiddenField($model, 'weixin_account_id'); ?>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'fid'); ?>
        <?php echo $form->dropDownList($model, 'fid', CHtml::listData(WeixinMenu::model()->getFirstLevelMenu($model->weixin_account_id), 'id', 'name'), array('empty' => '根目录', 'value' => '0')) ?>
        <?php echo $form->error($model, 'fid'); ?>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 21, 'maxlength' => 21)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'type'); ?>
        <?php echo $form->dropDownList($model, 'type', OutputHelper::getWeixinMenuTypeList()); ?>
        <?php echo $form->error($model, 'type'); ?>
    </div>

    <div class="control-group" id="WeixinMenu_key_div">
        <?php echo $form->labelEx($model, 'key'); ?>
        <?php echo $form->dropDownList($model, 'key', OutputHelper::getMenuFunctionList()); ?>
        <?php echo $form->error($model, 'key'); ?>
    </div>


    <div class="control-group" id="WeixinMenu_url_div">
        <?php echo $form->labelEx($model, 'url'); ?>
        <?php echo $form->dropDownList($model, 'url', OutputHelper::getMenuUrlList()); ?>
        <?php echo $form->error($model, 'url'); ?>
    </div>



    <div class="control-group">
        <?php echo $form->labelEx($model, 'status'); ?>
        <?php echo $form->dropDownList($model, 'status', OutputHelper::getStatusEnabledDisableList()); ?>
        <?php echo $form->error($model, 'status'); ?>
    </div>

    <div class="control-group buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<script>
    function typeSelect () {
        var type = $("#WeixinMenu_type").val();

        if (type == 'view') {
            $('#WeixinMenu_key_div').css({
                display: 'none'
            });
            $('#WeixinMenu_url_div').css({
                display: 'block'
            })
        } else if (type == 'click') {
            $('#WeixinMenu_key_div').css({
                display: 'block'
            });
            $('#WeixinMenu_url_div').css({
                display: 'none'
            })
        } else {
            $('#WeixinMenu_key_div').css({
                display: 'none'
            });
            $('#WeixinMenu_url_div').css({
                display: 'none'
            })
        }
    }


    $(document).ready(function () {

        typeSelect();
        //select
        $('#WeixinMenu_type').change(typeSelect);

    });
</script>