<?php
/* @var $this ShopController */
/* @var $model Shop */
?>

<?php
$this->breadcrumbs = array(
        '门店管理' => array('index'),
        '内容自定义',
);
?>
<h1>词汇自定义</h1>

<div class="form">

    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'shop-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => false,
    )); ?>


    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th class="span1">默认词汇</th>
            <th class="span11">自定义词汇</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($languages as $key => $value) :?>
            <tr>
                <td>
                    <?php echo $key; ?>
                </td>
                <td>
                    <input type="text" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>


    <div class="form-actions">
        <?php echo TbHtml::submitButton('保存', array(
                'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'size' => TbHtml::BUTTON_SIZE_LARGE,
        )); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->