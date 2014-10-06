<?php
/* @var $this SiteNotificationController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
        '网站通知',
);

?>

    <h1>网站通知</h1>
    <div class="control-group">
        <?php
        echo TbHtml::buttonGroup(array(
                array(
                        'label' => '添加通知',
                        'color' => 'success',
                        'url' => array('create')
                ))); ?>

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
                        'name' => 'title',
                        'type' => 'raw',
                        'value' => 'CHtml::link($data->title, $this->grid->controller->createUrl("update", array("id"=> $data->id)))',
                ),
                array(
                        'name' => 'dateline',
                        'value' => 'OutputHelper::timeFormat($data->dateline)',
                ),
                array(
                        'name' => 'expire',
                        'value' => 'OutputHelper::timeFormat($data->expire)',
                ),
                array(
                        'class' => 'bootstrap.widgets.TbButtonColumn',
                        'template' => '{update} {delete} ',
                        'buttons' => array()
                ),
        ),
)); ?>