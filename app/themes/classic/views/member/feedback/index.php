<?php
/* @var $this FeedbackController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
        '意见反馈',
);

$this->menu = array(
        array('label' => 'Create Feedback', 'url' => array('create')),
        array('label' => 'Manage Feedback', 'url' => array('admin')),
);
?>

    <h1>意见反馈</h1>

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
                'content',
                'reply',
                array(
                        'name' => 'default',
                        'type' => 'raw',
                        'value' => 'OutputHelper::timeFormat($data->dateline)',
                ),
                array(
                        'class' => 'bootstrap.widgets.TbButtonColumn',
                        'template' => '{update} {delete} ',
                        'buttons' => array()
                ),
        ),
)); ?>