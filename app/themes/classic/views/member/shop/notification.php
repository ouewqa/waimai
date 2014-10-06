<?php
/* @var $this ShopController */
/* @var $model Shop */
?>

<?php
$this->breadcrumbs = array(
        '门店管理' => array('index'),
        '云打印机',
);
?>
<h1>云打印机</h1>
<div class="btn-toolbar">
    <?php
    if ($this->shop->push_method == 'printer') {
        echo TbHtml::buttonGroup(array(
                array(
                        'label' => '检测状态',
                        'color' => 'info', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                        'buttonType' => 'ajaxButton', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                        'ajaxOptions' => array(
                                'success' => 'js:function(data){alert(data.msg)}',
                                'dataType' => 'JSON',
                                'url' => $this->createUrl('checkPrinter'),
                        ),

                ),
                array(
                        'label' => '测试打印',
                        'color' => 'warning', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                        'buttonType' => 'ajaxButton', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                        'ajaxOptions' => array(
                                'success' => 'js:function(data){alert(data.msg)}',
                                'dataType' => 'JSON',
                                'url' => $this->createUrl('test'),
                        ),

                )
        ));
    } else if ($this->shop->push_method == 'sms') {
        echo TbHtml::buttonGroup(array(
                array(
                        'label' => '发送测试短信',
                        'color' => 'info', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                        'buttonType' => 'ajaxButton', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                        'ajaxOptions' => array(
                                'success' => 'js:function(data){alert(data.msg)}',
                                'dataType' => 'JSON',
                                'url' => $this->createUrl('test'),
                        ),

                ),
        ));
    }
    ?>
</div>
<div class="form">

    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'shop-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => false,
    )); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->dropDownListControlGroup($model, 'push_method', OutputHelper::getPushMethodList(), array('span' => 5, 'maxlength' => 45, 'empty' => '不通知')); ?>

    <div id="method_sms">
        <?php echo $form->textFieldControlGroup($model, 'mobile', array('span' => 5, 'maxlength' => 45)); ?>
    </div>


    <div id="method_printer">
        <?php echo $form->dropDownListControlGroup($model, 'push_device', OutputHelper::getPushDeviceList(), array('span' => 5, 'maxlength' => 45, 'empty' => '请选择设备')); ?>

        <?php echo $form->textFieldControlGroup($model, 'push_device_no', array('span' => 5, 'maxlength' => 45)); ?>

        <?php echo $form->textFieldControlGroup($model, 'push_device_key', array('span' => 5, 'maxlength' => 45)); ?>

        <?php echo $form->textFieldControlGroup($model, 'number_of_copies', array('span' => 1, 'maxlength' => 1)); ?>
    </div>


    <div class="form-actions">
        <?php echo TbHtml::submitButton($model->isNewRecord ? '创建' : '保存', array(
                'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'size' => TbHtml::BUTTON_SIZE_LARGE,
        )); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->


<script>
    function typeSelect () {
        var type = $("#Shop_push_method").val();

        if (type == 'sms') {
            $('#method_sms').css({
                display: 'block'
            });
            $('#method_printer').css({
                display: 'none'
            });

        } else if (type == 'printer') {
            $('#method_sms').css({
                display: 'none'
            });
            $('#method_printer').css({
                display: 'block'
            });
        } else {
            $('#method_sms').css({
                display: 'none'
            });
            $('#method_printer').css({
                display: 'none'
            });
        }
    }


    $(document).ready(function () {

        typeSelect();
        //select
        $('#Shop_push_method').change(typeSelect);

    });
</script>
