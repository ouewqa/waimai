<?php
/* @var $this ShopDishController */
/* @var $model ShopDish */
?>

<?php
$this->breadcrumbs = array(
        '菜品管理' => array('index'),
        '添加',
);

$this->menu = array(
        array('label' => 'List ShopDish', 'url' => array('index')),
        array('label' => 'Manage ShopDish', 'url' => array('admin')),
);
?>

    <h1>添加菜品</h1>

<?php $this->renderPartial('_form', array('model' => $model, 'category' => $category)); ?>