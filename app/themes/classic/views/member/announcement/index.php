<?php
/* @var $this AnnouncementController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
        '门店公告',
);

$this->menu = array(
        array('label' => 'Create Announcement', 'url' => array('create')),
        array('label' => 'Manage Announcement', 'url' => array('admin')),
);
?>

    <h1>公告列表</h1>

    <div class="control-group">
        <?php
        echo TbHtml::buttonGroup(array(
                array(
                        'label' => '添加公告',
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
                        'name' => 'content',
                        'type' => 'raw',
                        'value' => 'CHtml::link(OutputHelper::strcut($data->content,60), $this->grid->controller->createUrl("update", array("id"=> $data->id)))',
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