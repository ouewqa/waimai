<?php
/* @var $this WeixinMessageController */
/* @var $model WeixinMessage */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'weixin-message-form',
            'enableAjaxValidation' => false,
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->hiddenField($model, 'type'); ?>
    <?php echo $form->hiddenField($model, 'io', array('value' => 'O')); ?>




    <div class="control-group">
        <?php $this->widget('bootstrap.widgets.TbTabs', array(
                'tabs' => array(
                        array(
                                'id' => 'text',
                                'label' => '文本',
                                'content' => $this->renderPartial(
                                                '_text',
                                                array(
                                                        'form' => $form,
                                                        'model' => $model,
                                                ),
                                                true),
                                'active' => true,
                        ),
                       /* array(
                                'id' => 'music',
                                'label' => '音乐', 'content' => $this->renderPartial(
                                        '_music',
                                        array(
                                                'form' => $form,
                                                'model' => $model,
                                        ),
                                        true)
                        ),

                        array(
                                'id' => 'image',
                                'label' => '图片', 'content' => '<p>Howdy, I\'m in Section 2.</p>'),
                        array(
                                'id' => 'voice',
                                'label' => '语音', 'content' => '<p>What up girl, this is Section 3.</p>'),
                        array(
                                'id' => 'video',
                                'label' => '视频', 'content' => '<p>What up girl, this is Section 4.</p>'),

                        array(
                                'id' => 'news',
                                'label' => '图文', 'content' => '<p>What up girl, this is Section 6.</p>'),*/
                ),
        )); ?>
    </div>


    <div class="control-group buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? '回复' : 'Save', array(
                'class' => 'btn btn-large btn-block btn-success'
        )); ?>
    </div>

    <?php

    $html = array();
    foreach ($replyModel as $key => $value) {
        $style = $value->io == 'I' ? 'style="background: #EFEFEF"' : 'style="background: #fff"';
        $author = $value->io == 'I' ? CHtml::link($user->nickname, $this->createUrl('/console/weixin/update', array('id' => $user->id)), array('target' => '_blank')) : $this->account->name;


        switch ($value->type) {
            case 'image':
                $value->message = '<img src="' . $value->message . '" />';
                break;
        }
        $html[] =
                '
                <div class="control-group">
                    <label>
                    ' . $author . ' <span class="label label-info">' . OutputHelper::timeFormat($value->dateline) . '</span> <span class="label label-success">' . OutputHelper::getWeixinMessageTypeList($value->type) . '</span>
                    </label>
                    <div class="well" ' . $style . '>
                    ' . nl2br($value->message) . '
                    </div>
                </div>
            ';
    }
    //krsort($html);
    echo implode($html);
    ?>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<script>
    function setTypeValue (event) {
        var id, target;
        target = event.target;
//        console.log(target);

        id = $(target).attr('href').substr(1);
        console.log(id);
        $('#WeixinMessage_type').val(id);
    }
</script>