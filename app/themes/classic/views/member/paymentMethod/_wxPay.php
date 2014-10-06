<div class="modal-header">
    <button data-dismiss="modal" class="close" type="button">×</button>
    <h3><?php echo $model->name; ?></h3></div>
<div class="modal-body">
    <?php
    /* @var $this PaymentMethodController */
    /* @var $model PaymentMethod */
    /* @var $form TbActiveForm */

    $config['status'] = isset($config['status']) ? $config['status'] : 'N';


    /**
     *
     * 支付授权目录   member/public/wxPay/api/
     * 支付请求实例   member/public/wxPay/api/10000_client
     * 维权通知URL  member/public/wxPay/api/10000_safeguard
     * 告警通知URL  member/public/wxPay/api/10000_alert
     * Paysignkey
    Partnerid
    Partnerkey
     *
     * 选择支持微信支付功能的 认证服务号 进行设置，如果您没有认证的服务号请进入 微信公众账号 进行设置
    申请微信支付所需参数详情，请点击 申请微信支付参数 进行参考
     *
     */

    $path = sprintf('%smember/public/wxPay/api/', Yii::app()->request->hostInfo, $this->account->id);
    $client = sprintf('%smember/public/wxPay/api/%d_client', Yii::app()->request->hostInfo, $this->account->id);
    $safeguard = sprintf('%smember/public/wxPay/api/%d_safeguard', Yii::app()->request->hostInfo, $this->account->id);
    $alert = sprintf('%smember/public/wxPay/api/%d_alert', Yii::app()->request->hostInfo, $this->account->id);
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

        <?php echo $form->textFieldControlGroup($model, 'name', array('span' => 5, 'maxlength' => 45)); ?>


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