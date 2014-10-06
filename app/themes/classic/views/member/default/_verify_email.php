<?php
/* @var $this AdminController */
/* @var $model Admin */
/* @var $form CActiveForm */
?>

<div class="form">
    <form id="email-verify-form" action="/member/default/verify/type/email" method="post" _lpchecked="1">
        <p class="help-block">Fields with <span class="required">*</span> are required.</p>


        <div class="control-group">
            <label class="control-label" for="Verify_email">邮件地址</label>

            <div class="controls">
                <input maxlength="64"
                       disabled="disabled"
                       name="Verify[email]"
                       id="Verify_email"
                       class="span4"
                       value="<?php echo $model->email; ?>"
                       type="text">

            </div>
            <p class="label">更新邮件地址，进入个人信息。</p>

        </div>

        <?php

        if ($model->email_is_verify == 'Y') {
            ?>
            <blockquote>你的邮箱已经验证成功。</blockquote>
        <?php
        } else {

            ?>
            <div class="control-group">
                <?php echo TbHtml::button(
                        '发送验证码',
                        array('loading' => 'Loading...', 'color' => 'success', 'class' => 'sendButton')
                ); ?>

            </div>
            <div class="control-group"><label class="control-label required" for="Verify_code">验证码
                    <span class="required">*</span></label>

                <div class="controls"><input maxlength="6" name="code[email]" id="email_code" class="span2" type="text">
                </div>
            </div>
            <div class="control-group">
                <?php echo TbHtml::button(
                        '提交验证码',
                        array('loading' => '验证中...', 'color' => 'info', 'class' => 'verifyButton')
                ); ?>

            </div>

        <?php

        }

        ?>


    </form>


</div><!-- form -->

<script>

    $(document).ready(function () {

        $('.sendButton').click(function () {
            var btn = $(this);
            btn.button('loading');

            $.get("<?php echo $this->createUrl('sendVerifyCode',  array('type'=> 'email')) ;?>", {

                    },
                    function (data) {
                        //console.log(data);
                        if (data.status) {
                            alert(data.msg);
                        }
                        btn.button('reset'); // call the reset function
                    },
                    'json'
            );
        });


        $('.verifyButton').click(function () {
            var btn = $(this),
                    code = $('#email_code').val();

            if (!code) {
                $('#email_code').focus();
            } else {
                btn.button('loading');

                $.post("<?php echo $this->createUrl('verify',  array('type'=> 'email')) ;?>", {
                            code: code
                        },
                        function (data) {
                            //console.log(data);
                            if (data.status) {
                                location.reload();
                            }
                            btn.button('reset'); // call the reset function
                        },
                        'json'
                );
            }

        });


    })


</script>