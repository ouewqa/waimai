<?php
/* @var $this SiteProductController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
        '网站产品',
);
?>

    <h1>产品列表</h1>
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
                        'value' => 'CHtml::link($data->name, $this->grid->controller->createUrl("/member/siteProductOrder/create", array("id"=> $data->id)))',
                ),
                'description:html',
                'price',
                array(
                        'class' => 'bootstrap.widgets.TbButtonColumn',
                        'template' => '{buy} ',
                        'buttons' => array(
                                'buy' => array
                                (
                                        'label' => '购买',
                                        'icon' => 'icon-shopping-cart',
                                        'url' => 'Yii::app()->createUrl("/member/siteProductOrder/create", array("id" => $data->id))',
                                ),
                        )
                ),
        ),
)); ?>