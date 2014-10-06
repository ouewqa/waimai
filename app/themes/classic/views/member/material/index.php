<?php
/* @var $this MaterialController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
        '自定义回复',
);

$this->menu = array(
        array('label' => 'Create Material', 'url' => array('create')),
        array('label' => 'Manage Material', 'url' => array('admin')),
);
?>

    <h1>自定义回复</h1>
    <div class="control-group">
        <?php
        echo TbHtml::buttonGroup(array(
                array(
                        'label' => '添加自定义回复',
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
                'keyword',
                'title',
                array(
                        'name' => 'type',
                        'header' => '回复类型',
                        'type' => 'raw',
                        'value' => 'OutputHelper::getResponseTypeList($data->type)',
                ),
                array(
                        'class' => 'bootstrap.widgets.TbButtonColumn',
                        'template' => '{update} {delete} ',
                        'buttons' => array()
                ),
        ),
)); ?>