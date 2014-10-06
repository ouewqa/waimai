<header class="bar bar-nav">
    <?php echo CHtml::link('<small>会员中心</small>', array('feedback/index'), array(
            'class' => 'icon icon-left-nav pull-left',
            'data-transition' => 'slide-out',
        #'data-ignore' => 'push'
    )); ?>
    <h1 class="title">意见反馈</h1>
</header>

<div class="content">

    <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'feedback-form',
            'htmlOptions' => array(
                    'class' => 'input-group',
            ),
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => false,
    )); ?>

    <?php echo $form->errorSummary($model); ?>


    <?php echo $form->textField($model, 'weixin_account', array(
            'size' => 45,
            'maxlength' => 45,
            'placeholder' => '个人微信号',
            'value' => $this->weixin->weixin_account,
    )); ?>


    <?php echo $form->textField($model, 'mobile', array(
            'size' => 11,
            'maxlength' => 11,
            'placeholder' => '手机号码',
            'value' => $this->weixin->mobile,
    )); ?>

    <!-- --><?php /*echo $form->textField($model, 'email', array('size' => 45, 'maxlength' => 45,
            'placeholder' => '电子邮箱'
    )); */
    ?>

    <?php echo $form->textArea($model,
            'content', array(
                    'size' => 60,
                    'maxlength' => 1024,
                    'placeholder' => '您的意见反馈',
                    'rows' => '5'
            )); ?>

    <div class="content-padded">
        <button class="btn btn-positive btn-block">提交反馈</button>
    </div>

    <?php $this->endWidget(); ?>


</div>
