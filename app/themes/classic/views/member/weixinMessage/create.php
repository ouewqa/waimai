<?php
/* @var $this WeixinMessageController */
/* @var $model WeixinMessage */

$this->breadcrumbs = array(
        '微信消息' => array('index'),
        '回复',
);
?>

    <h1>微信消息回复</h1>

<?php echo $this->renderPartial('_form', array('model' => $model, 'replyModel' => $replyModel, 'user' => $user)); ?>