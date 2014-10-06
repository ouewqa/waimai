<?php
/* @var $this SiteProductOrderController */
/* @var $model SiteProductOrder */
?>

<?php
$this->breadcrumbs = array(
        '增值服务' => array('index'),
        '购买',
);
?>

    <h1>购买<?php echo $product->name; ?></h1>

<?php $this->renderPartial('_form', array(
        'model' => $model,
        'product' => $product,
)); ?>