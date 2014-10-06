<?php
/* @var $this SiteProductCategoryController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
        '产品分类',
);
?>

<h1>产品分类</h1>
<div class="control-group">
    <?php
    echo TbHtml::buttonGroup(array(
            array(
                    'label' => '添加产品',
                    'color' => 'success',
                    'url' => array('create')
            ))); ?>

</div>

<div class="btn-toolbar pull-left">
    <?php

    $items = array();
    foreach (OutputHelper::getStatusEnabledDisableList() as $key => $value) {
        $items[] = array(
                'label' => $value,
                'color' => $status == $key ? TbHtml::BUTTON_COLOR_INFO : null,
                'url' => $this->createUrl('index', array(
                                'status' => $key,
                        ))
        );
    }
    echo TbHtml::buttonGroup($items); ?>
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
                array(
                        'name' => 'ob',
                        'header' => '优先级（越小越优先）',
                        'type' => 'raw',
                        'value' => 'CHtml::tag("input", array(
                                "class" => "span2",
                                "value" => $data->ob,
                                "data-id" => $data->id,
                            )
                        )',
                ),
                array(
                        'name' => 'status',
                        'value' => 'OutputHelper::getStatusEnabledDisableList($data->status)',
                ),
                array(
                        'class' => 'bootstrap.widgets.TbButtonColumn',
                        'template' => '{update} {delete} ',
                        'buttons' => array()
                ),
        ),
)); ?>

<script>
    $('.grid-view input').blur(function () {
        $.post("<?php echo $this->createUrl('update');?>/id/" + $(this).attr('data-id'), { 'SiteProductCategory': {ob: $(this).attr('value')}, 'ajax': true },
                function (data) {
                    //alert("Data Loaded: " + data);
                });
    })
</script>