<?php
/* @var $this SiteProductOrderController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
        '产品订单',
);

$this->menu = array(
        array('label' => 'Create SiteProductOrder', 'url' => array('create')),
        array('label' => 'Manage SiteProductOrder', 'url' => array('admin')),
);
?>

    <h1>产品订单</h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
        'dataProvider' => $dataProvider,
        'type' => array(
                TbHtml::GRID_TYPE_STRIPED,
                TbHtml::GRID_TYPE_BORDERED,
                TbHtml::GRID_TYPE_HOVER
        ),
    #'filter' => $person,
        //'template' => "{items}",
        'columns' => array(
                'id',
                'order_sn',
                array(
                        'name' => 'subject',
                        'type' => 'raw',
                        'value' => 'CHtml::link($data->subject, $this->grid->controller->createUrl("update", array("id"=> $data->id)))',
                ),
                'payment',
                'buyer',
                'price',
                'number',
                array(
                        'name' => 'dateline',
                        'value' => 'OutputHelper::timeFormat($data->dateline)',
                ),
                array(
                        'name' => 'status',
                        'value' => 'OutputHelper::getPaidStatusList($data->status)',
                ),
        ),
)); ?>