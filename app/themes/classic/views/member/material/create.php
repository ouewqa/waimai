<?php
/* @var $this MaterialController */
/* @var $model Material */
?>

<?php
$this->breadcrumbs = array(
        '素材管理' => array('index'),
        '添加',
);

$this->menu = array(
        array('label' => 'List Material', 'url' => array('index')),
        array('label' => 'Manage Material', 'url' => array('admin')),
);

if (isset($model->keyword) && $model->keyword == 'followResponse') {
    $title = '关注后自动回复';
} else if (isset($model->keyword) && $model->keyword == 'defaultResponse') {
    $title = '默认回复';
} else if (isset($model->keyword) && $model->keyword == 'lbsResponse') {
    $title = '地理位置回复';
} else {
    $title = '添加素材';
}

?>


    <h1><?php echo $title; ?></h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>