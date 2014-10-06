<?php
/* @var $this SiteProductController */
/* @var $model SiteProduct */
?>

<?php
$this->breadcrumbs = array(
        '增值服务' => array('index'),
        $model->name,
);

?>

    <h1>#<?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
        'htmlOptions' => array(
                'class' => 'table table-striped table-condensed table-hover',
        ),
        'data' => $model,
        'attributes' => array(
                'id',
                'site_product_category_id',
                'name',
                'description',
                'image',
                'content:html',
                'dateline',
        ),
)); ?>