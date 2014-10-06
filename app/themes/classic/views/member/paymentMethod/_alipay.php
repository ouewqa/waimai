<div class="modal-header">
    <button data-dismiss="modal" class="close" type="button">×</button>
    <h3><?php echo $model->name; ?></h3>
</div>
<div class="modal-body">
    <?php
    /* @var $this PaymentMethodController */
    /* @var $model PaymentMethod */
    /* @var $form TbActiveForm */

    $config['status'] = isset($config['status']) ? $config['status'] : 'N';
    $config['pid'] = isset($config['pid']) ? $config['pid'] : '';
    $config['key'] = isset($config['key']) ? $config['key'] : '';
    $config['seller'] = isset($config['seller']) ? $config['seller'] : '';

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
            <label class="control-label required" for="Shop_name">合作者身份(PID)
                <span class="required">*</span>
            </label>

            <div class="controls">
                <input maxlength="45"
                       name="pid"
                       id="pid"
                       value="<?php echo $config['pid'] ?>"
                       class="span5"
                       type="text" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label required" for="Shop_name">安全校验码(Key)
                <span class="required">*</span>
            </label>

            <div class="controls">
                <input maxlength="45"
                       name="key"
                       value="<?php echo $config['key'] ?>"
                       id="key"
                       class="span5"
                       type="text" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label required" for="Shop_name">支付宝账号
                <span class="required">*</span>
            </label>

            <div class="controls">
                <input maxlength="45"
                       name="seller"
                       value="<?php echo $config['seller'] ?>"
                       id="seller"
                       class="span5"
                       type="text" />
            </div>
        </div>


        <div class="control-group">
            <label class="control-label required" for="Shop_name">是否开通支付宝付款？
                <span class="required">*</span>
            </label>

            <div class="controls">

                <select maxlength="1" class="span5" name="status" id="status">
                    <option value="Y"<?php echo $config['status'] == 'Y' ? ' selected="selected"' : '' ?>>开通
                    </option>
                    <option value="N"<?php echo $config['status'] != 'Y' ? ' selected="selected"' : '' ?>>关闭
                    </option>
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