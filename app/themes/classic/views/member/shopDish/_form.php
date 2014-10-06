<?php
/* @var $this ShopDishController */
/* @var $model ShopDish */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'shop-dish-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => false,
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
    )); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->dropDownListControlGroup(
            $model,
            'shop_dish_category_id',
            CHtml::listData($category, 'id', 'name'),
            array('span' => 5, 'maxlength' => 1));
    ?>

    <?php echo $form->textFieldControlGroup($model, 'name', array('span' => 5, 'maxlength' => 45)); ?>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'image'); ?>

        <?php if (!$model->isNewRecord && $model->image) { ?>
            <p>
                <?php echo CHtml::image($model->image, '', array('style' => 'max-width:300px;')); ?>
            </p>
            <p>
                <input type="hidden" name="image2" class="span12" value="<?php echo $model->image; ?>" />
            </p>

        <?php } ?>
        <?php echo $form->error($model, 'image'); ?>
    </div>
    <div class="control-group">
        <?php echo CHtml::activeFileField($model, 'image', array('value' => $model->image)); ?>
    </div>


    <div class="control-group">
        <label class="control-label" for="ShopDish_price">价格</label>

        <div class="controls">
            <div class="input-prepend">
                <span class="add-on">¥</span>
                <?php $this->widget('yiiwheels.widgets.maskmoney.WhMaskMoney', array(
                        'name' => 'ShopDish[price]',
                        'id' => 'ShopDish_price',
                        'value' => $model->price,
                ));?>
            </div>

        </div>
    </div>


    <?php /*echo $form->textFieldControlGroup($model, 'discount', array('span' => 5, 'maxlength' => 45)); */ ?>


    <?php
    $this->widget('ext.ueditor.Ueditor', array(
            'model' => $model,
            'attribute' => 'description',
    ));
    ?>

    <?php echo $form->dropDownListControlGroup(
            $model,
            'status',
            OutputHelper::getShopDishStatusList(),
            array('span' => 5, 'maxlength' => 1));
    ?>


    <div class="form-actions">
        <?php echo TbHtml::submitButton($model->isNewRecord ? '创建' : '保存', array(
                'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'size' => TbHtml::BUTTON_SIZE_LARGE,
        )); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->