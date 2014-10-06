<?php
/* @var $this ShopDishController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
        '菜品列表',
);

$this->menu = array(
        array('label' => 'Create ShopDish', 'url' => array('create')),
        array('label' => 'Manage ShopDish', 'url' => array('admin')),
);
?>

<h1>菜品</h1>
<div class="control-group">
    <?php
    echo TbHtml::buttonGroup(array(
            array(
                    'label' => '创建菜品',
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
        //'template' => "{items}",
        'columns' => array(
                'id',
                array(
                        'name' => 'name',
                        'type' => 'raw',
                        'value' => 'CHtml::link($data->name, $this->grid->controller->createUrl("update", array("id"=> $data->id)))',
                ),
               /* array(
                        'name' => 'shop_dish_category_id',
                        'header' => '分类',
                        'value' => '$data->shopDishCategory->name',
                ),*/
                'price',
                /*'discount',*/

                array(
                        'name' => 'updatetime',
                        'header' => '更新时间',
                        'value' => 'OutputHelper::timeFormat($data->updatetime)',
                ),
                array(
                        'name' => 'ob',
                        'header' => '推荐指数（越小越优先）',
                        'type' => 'raw',
                        'value' => 'CHtml::tag("input", array(
                                "class" => "span2",
                                "value" => $data->ob,
                                "data-id" => $data->id,
                            )
                        )',
                ),
                array(
                        'class' => 'bootstrap.widgets.TbButtonColumn',
                        'template' => '{album} {update} {delete} ',
                        'buttons' => array(
                                'album' => array(
                                        'label' => '更多图片',
                                        'icon' => 'tags',
                                        'url' => 'Yii::app()->createUrl("/member/shopDishAlbum/index", array("shop_dish_id"=>$data->id))',

                                )
                        )
                ),
        ),
)); ?>


<script>
    $('.grid-view input').blur(function () {
        $.post("<?php echo $this->createUrl('update');?>/id/" + $(this).attr('data-id'), { 'ShopDish': {ob: $(this).attr('value')}, 'ajax': true },
                function (data) {
                    //alert("Data Loaded: " + data);
                });
    })
</script>