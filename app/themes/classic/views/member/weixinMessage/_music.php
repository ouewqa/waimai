<div class="control-group">
    <?php echo $form->labelEx($model, 'music_title'); ?>
    <?php echo $form->textField($model, 'music_title'); ?>
    <?php echo $form->error($model, 'music_title'); ?>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'music_description'); ?>
    <?php echo $form->textField($model, 'music_description'); ?>
    <?php echo $form->error($model, 'music_description'); ?>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'music_url'); ?>
    <?php echo $form->textField($model, 'music_url'); ?>
    <?php echo $form->error($model, 'music_url'); ?>
</div>