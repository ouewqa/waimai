<?php
/* @var $this ShopDishCategoryController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
        '菜品分类',
);

$this->menu = array(
        array('label' => 'Create ShopDishCategory', 'url' => array('create')),
        array('label' => 'Manage ShopDishCategory', 'url' => array('admin')),
);
?>

<h1>菜品分类</h1>
<div class="control-group">
    <?php
    echo TbHtml::buttonGroup(array(
            array(
                    'label' => '创建分类',
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
        $.post("<?php echo $this->createUrl('update');?>/id/" + $(this).attr('data-id'), { 'ShopDishCategory': {ob: $(this).attr('value')}, 'ajax': true },
                function (data) {
                    //alert("Data Loaded: " + data);
                });
    })
</script>