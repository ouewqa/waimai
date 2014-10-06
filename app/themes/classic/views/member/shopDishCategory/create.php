<?php
/* @var $this ShopDishCategoryController */
/* @var $model ShopDishCategory */
?>

<?php
$this->breadcrumbs = array(
        '菜品分类' => array('index'),
        '添加',
);

$this->menu = array(
        array('label' => 'List ShopDishCategory', 'url' => array('index')),
        array('label' => 'Manage ShopDishCategory', 'url' => array('admin')),
);
?>

    <h1>添加菜品分类</h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>