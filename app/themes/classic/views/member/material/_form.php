<?php
/* @var $this MaterialController */
/* @var $model Material */
/* @var $form TbActiveForm */
?>

<div class="form">
    <?php $this->widget('bootstrap.widgets.TbTabs', array(
            'tabs' => array(
                    array(
                            'label' => '图文回复',
                            'content' => $this->renderPartial(
                                            '_news',
                                            array(
                                                    'model' => $model,
                                            ),
                                            true),
                            'active' => $model->type == 'N' ? true : false
                    ),
                    array(
                            'label' => '文字回复',
                            'content' => $this->renderPartial(
                                            '_text',
                                            array(
                                                    'model' => $model,
                                            ),
                                            true),
                            'active' => $model->type == 'T' ? true : false,
                    ),


            ),
    )); ?>
</div><!-- form -->