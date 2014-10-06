<div class="control-group">
    <?php echo $form->labelEx($model, 'message'); ?>
    <?php echo $form->textArea($model, 'message', array('rows' => 5, 'class' => 'span12', 'size' => 60, 'maxlength' => 2048)); ?>
    <?php echo $form->error($model, 'message'); ?>
</div>