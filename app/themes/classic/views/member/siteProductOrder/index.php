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
                        'value' => 'CHtml::link($data->subject, $this->grid->controller->createUrl("/member/siteProductOrder/create", array("id"=> $data->site_product_id)))',
                ),
                'payment',
                array(
                        'name' => 'price',
                        'header' => '价格',
                        'value' => '$data->siteProduct->price',
                ),
                array(
                        'name' => 'number',
                        'header' => '数量',
                        'value' => '$data->siteProduct->number',
                ),
                array(
                        'name' => 'dateline',
                        'value' => 'OutputHelper::timeFormat($data->dateline)',
                ),
                array(
                        'name' => 'status',
                        'value' => 'OutputHelper::getPaidStatusList($data->status)',
                ),
                array(
                        'name' => '操作',
                        'type' => 'raw',
                        'value' => '$data->status!="Y" ? CHtml::link("付款", $this->grid->controller->createUrl("/member/siteProductOrder/repay", array("id"=> $data->id))) : ""',
                ),
        ),
)); ?>