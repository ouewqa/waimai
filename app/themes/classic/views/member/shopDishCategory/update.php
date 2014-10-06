<?php
/* @var $this ShopDishCategoryController */
/* @var $model ShopDishCategory */
?>

<?php
$this->breadcrumbs = array(
        '菜品分类' => array('index'),
        $model->name,
);

$this->menu = array(
        array('label' => 'List ShopDishCategory', 'url' => array('index')),
        array('label' => 'Create ShopDishCategory', 'url' => array('create')),
        array('label' => 'View ShopDishCategory', 'url' => array('view', 'id' => $model->id)),
        array('label' => 'Manage ShopDishCategory', 'url' => array('admin')),
);
?>

    <h1>更新分类： <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>