<div class="modal-header">
    <button data-dismiss="modal" class="close" type="button">×</button>
    <h3><?php echo $model->name; ?></h3></div>
<div class="modal-body">
    <?php
    /* @var $this PaymentMethodController */
    /* @var $model PaymentMethod */
    /* @var $form TbActiveForm */

    $config['status'] = isset($config['status']) ? $config['status'] : 'N';

    ?>

    <div class="form">

        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'payment-method-form',
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
                'enableAjaxValidation' => false,
        )); ?>
        <div class="control-group">
            <label class="control-label required" for="status">是否开通货到付款？
                <span class="required">*</span></label>

            <div class="controls">
                <select maxlength="1" class="span5" name="status" id="status">
                    <option value="Y"<?php echo $config['status'] == 'Y' ? ' selected="selected"' : '' ?>>开通
                    </option>
                    <option value="N"<?php echo $config['status'] != 'Y' ? ' selected="selected"' : '' ?>>关闭</option>
                </select>
            </div>
        </div>

        <?php $this->endWidget(); ?>

    </div>
    <!-- form -->

</div>
<div class="modal-footer">
    <button data-dismiss="modal" class="btn btn-primary" name="yt3" type="button" onclick="post();">保存</button>
    &nbsp;
    <button data-dismiss="modal" class="btn" name="yt4" type="button">关闭</button>
</div>

<script>
    function post () {
        $.post($("#payment-method-form").attr('action'), $("#payment-method-form").serialize());
    }

</script>