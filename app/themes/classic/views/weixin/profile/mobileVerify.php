<header class="bar bar-nav">
    <?php echo CHtml::link('60', '#', array(
            'id' => 'btn_mobile_verify',
            'class' => 'btn btn-negative pull-right',
            'data-id' => $model->id,
    ));?>
    <h1 class="title">验证手机号码</h1>
</header>

<div class="content">


    <ul class="table-view">
        <li class="table-view-cell table-view-divider">验证码已成功发到你的手机</li>
    </ul>


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


    <div class="input-row">
        <label>手机号码</label>
        <?php echo $form->textField(
                $model,
                'mobile',
                array(
                        'size' => 11,
                        'maxlength' => 11,
                        'placeholder' => '手机号码',
                        'readonly' => 'readonly',
                )); ?>
    </div>

    <div class="input-row">
        <label for="code">验证码</label>
        <input size="6" maxlength="6" placeholder="验证码" name="code" id="code" type="text">
    </div>


    <div class="content-padded">
        <button class="btn btn-positive btn-block">提交</button>
    </div>

    <?php $this->endWidget(); ?>
</div>

<script>


    var wait = 2;
    function countZero (o) {
        if (wait <= 0) {
            o.removeAttr("disabled");
            o.html("重新发送");
        } else {
            o.attr("disabled", true);
            o.html("重新发送(" + wait + ")");
            wait--;
            setTimeout(function () {
                countZero(o);
            }, 1000)
        }
    }

    $(document).ready(function () {
        var obj = $('#btn_mobile_verify');
        countZero(obj);
        obj.click(function () {
            //alert($('#UsedMobile_mobile').val());
            if (wait <= 0) {
                $.get('/weixin/profile/sendSmsVerifyCode', { mobile: $('#UsedMobile_mobile').val() },
                        function (data) {
                            wait = 60;
                            countZero(obj);
                            alert(data.msg);
                        }, 'json');
            }
        })
    })


</script>