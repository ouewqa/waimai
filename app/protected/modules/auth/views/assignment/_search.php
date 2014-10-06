<?php
/* @var $this WeixinController */
/* @var $model Weixin */
/* @var $form CActiveForm */
?>

<div class="wide form">

    <?php $form = $this->beginWidget('CActiveForm', array(
            'action' => Yii::app()->createUrl($this->route),
            'method' => 'get',
    )); ?>

    <div class="control-group">
        <?php echo $form->label($model, 'id'); ?>
        <?php echo $form->textField($model, 'id', array('size' => 45, 'maxlength' => 45)); ?>
    </div>

    <div class="control-group">
        <?php echo $form->label($model, 'username'); ?>
        <?php echo $form->textField($model, 'username', array('size' => 45, 'maxlength' => 45)); ?>
    </div>


    <div class="control-group">
        <?php echo $form->label($model, 'mobile'); ?>
        <?php echo $form->textField($model, 'mobile', array('size' => 45, 'maxlength' => 45)); ?>
    </div>

    <div class="control-group">
        <?php echo $form->label($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', array('size' => 45, 'maxlength' => 45)); ?>
    </div>


    <div class="control-group buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->