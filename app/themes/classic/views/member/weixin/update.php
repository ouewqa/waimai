<?php
/* @var $this WeixinController */
/* @var $model Weixin */

$this->breadcrumbs = array(
        '粉丝管理' => array('index'),
        '更新',
);
?>

    <h1>更新用户信息 <?php echo $model->nickname; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>