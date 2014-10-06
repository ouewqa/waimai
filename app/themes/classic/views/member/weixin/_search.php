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
        <?php echo $form->label($model, 'nickname'); ?>
        <?php echo $form->textField($model, 'nickname', array('size' => 45, 'maxlength' => 45)); ?>
    </div>

    <div class="control-group">
        <?php echo $form->label($model, 'realname'); ?>
        <?php echo $form->textField($model, 'realname', array('size' => 45, 'maxlength' => 45)); ?>
    </div>


    <div class="control-group">
        <?php echo $form->label($model, 'sex'); ?>
        <?php echo $form->dropDownList($model, 'sex', OutputHelper::getSexList()); ?>
    </div>

    <div class="control-group">
        <?php echo $form->label($model, 'province'); ?>
        <?php echo $form->textField($model, 'province', array('size' => 45, 'maxlength' => 45)); ?>
    </div>

    <div class="control-group">
        <?php echo $form->label($model, 'city'); ?>
        <?php echo $form->textField($model, 'city', array('size' => 45, 'maxlength' => 45)); ?>
    </div>

    <div class="control-group">
        <?php echo $form->label($model, 'mobile'); ?>
        <?php echo $form->textField($model, 'mobile', array('size' => 11, 'maxlength' => 11)); ?>
    </div>

    <div class="control-group">
        <?php echo $form->label($model, 'qq'); ?>
        <?php echo $form->textField($model, 'qq', array('size' => 45, 'maxlength' => 45)); ?>
    </div>

    <div class="control-group buttons">
        <?php echo CHtml::submitButton('搜索'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->