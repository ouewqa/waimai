<?php
/* @var $this ShopController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
        '门店管理',
);

$this->menu = array(
        array('label' => 'Create Shop', 'url' => array('create')),
        array('label' => 'Manage Shop', 'url' => array('admin')),
);
?>
    <h1>门店列表</h1>
    <div class="control-group">
        <?php
        echo TbHtml::buttonGroup(array(
                array(
                        'label' => '创建门店',
                        'color' => 'success',
                        'url' => array('create')
                )
        )); ?>

    </div>

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
                array(
                        'name' => 'name',
                        'type' => 'raw',
                        'value' => 'CHtml::link($data->name, $this->grid->controller->createUrl("update", array("id"=> $data->id)))',
                ),
                'mobile',
                'opening_time_start',
                'opening_time_end',
                'minimum_charge',
                'express_fee',
                /*array(
                        'name' => 'default',
                        'type' => 'raw',
                        'value' => 'OutputHelper::getDefaultStatusList($data->default)',
                ),*/
                array(
                        'class' => 'bootstrap.widgets.TbButtonColumn',
                        'template' => '{update} {delete} ',
                        'buttons' => array()
                ),
        ),
)); ?>