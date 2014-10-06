<div class="form">


    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'site-product-order-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => false,
    )); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->hiddenField($model, 'subject', array(
            'value' => $product->name
    )); ?>

    <?php echo $form->hiddenField($model, 'description', array(
            'value' => $product->description
    )); ?>

    <?php echo $form->hiddenField($model, 'price', array(
            'value' => $product->price
    )); ?>
    <?php echo $form->hiddenField($model, 'number', array(
            'value' => 1
    )); ?>

    <?php echo $form->hiddenField($model, 'money', array(
            'value' => $product->price
    )); ?>


    <table class="table table-hover">
        <thead>
        <tr>
            <th>名称</th>
            <th><?php echo $product->name; ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>价格</td>
            <td>¥ <?php echo $product->price; ?> 元</td>
        </tr>
        <tr>
            <td>描述</td>
            <td><?php echo $product->content; ?></td>
        </tr>

        <tr>
            <td>支付方式</td>
            <td>支付宝</td>
        </tr>

        </tbody>
    </table>


    <div class="form-actions">
        <?php echo TbHtml::submitButton($model->isNewRecord ? '购买' : '购买', array(
                'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'size' => TbHtml::BUTTON_SIZE_LARGE,
        )); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->