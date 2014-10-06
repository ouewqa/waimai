<?php
/**
 * Created by PhpStorm.
 * User: Keen
 * Date: 14-8-2
 * Time: 上午12:10
 * To change this template use File | Settings | File Templates.
 */

?>
<div id="register-wraper">

    <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'register-form',
            'enableAjaxValidation' => false,
    )); ?>

    <legend>注册账号</legend>

    <?php echo $form->errorSummary($model); ?>

    <div class="body">

        <label>用 户 名 ：<?php echo $form->textField($model, 'username'); ?></label>
        <label>密　　码：<?php echo $form->passwordField($model, 'password'); ?></label>
        <label>确认密码：<?php echo $form->passwordField($model, 'repassword'); ?></label>
        <label>手机号码：<?php echo $form->textField($model, 'mobile'); ?></label>
        <!--<label>E-mail：<?php /*echo $form->textField($model, 'email'); */?></label>-->
        <label class="checkbox inline">
            <input type="checkbox" name="saveLoginStatus" value="true"> 同意并遵守网站协议 <a href="#">查看网站协议</a>
        </label>
    </div>

    <div class="footer">

        <?php echo CHtml::submitButton('注册账号', array(
                'class' => 'btn btn-success',
        )); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>

<footer class="white navbar-fixed-bottom">
    已经注册账号了? <a href="<?php
    echo $this->createUrl(
            'login'
    );
    ?>" class="btn btn-black">点击登陆</a>
</footer>
