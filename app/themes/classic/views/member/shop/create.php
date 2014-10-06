<?php
/* @var $this ShopController */
/* @var $model Shop */
?>

<?php
$this->breadcrumbs = array(
        '门店管理' => array('index'),
        '创建',
);


?>


    <h1>添加门店</h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>