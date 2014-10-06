<?php
/**
 * Created by PhpStorm.
 * User: Keen
 * Date: 14-8-2
 * Time: 上午12:09
 * To change this template use File | Settings | File Templates.
 */
?>
<div id="login-wraper">
    <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'admin-register-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // See class documentation of CActiveForm for details on this,
        // you need to use the performAjaxValidation()-method described there.
            'enableAjaxValidation' => false,
            'htmlOptions' => array(
                    'class' => "form login-form"
            )
    )); ?>

    <legend>请输入用户名和密码！</legend>

    <?php if (Yii::app()->user->hasFlash('loginStatus')): ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo Yii::app()->user->getFlash('loginStatus'); ?>
        </div>

        <?php
        Yii::app()->clientScript->registerScript(
                'myHideEffect',
                '$(".alert").animate({opacity: 1.0}, 10000).fadeOut("slow");',
                CClientScript::POS_READY
        );
        ?>
    <?php endif; ?>

    <div class="body">
        <label>用户名：<?php echo $form->textField($model, 'username'); ?></label>
        <label>密　码：<?php echo $form->passwordField($model, 'password'); ?></label>
        <label class="checkbox inline">
            <input type="checkbox" name="saveLoginStatus" value="1"> 一周内自动登陆
        </label>
    </div>

    <div class="footer">

        <?php echo CHtml::submitButton('登陆', array(
                'class' => 'btn btn-success',
        )); ?>
    </div>

    <?php $this->endWidget(); ?>

</div>

<footer class="white navbar-fixed-bottom">
    还没注册账号？ <a href="<?php
    echo $this->createUrl(
            'register'
    );
    ?>" class="btn btn-black">点击注册</a>
</footer>