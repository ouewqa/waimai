<?php
/* @var $this SiteHelpController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
        '网站帮助',
);
?>

    <h1>网站帮助</h1>
    <div class="control-group">
        <?php
        echo TbHtml::buttonGroup(array(
                array(
                        'label' => '添加帮助',
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
                        'name' => 'title',
                        'type' => 'raw',
                        'value' => 'CHtml::link($data->title, $this->grid->controller->createUrl("update", array("id"=> $data->id)))',
                ),
                array(
                        'name' => 'dateline',
                        'value' => 'OutputHelper::timeFormat($data->dateline)',
                ),
                array(
                        'class' => 'bootstrap.widgets.TbButtonColumn',
                        'template' => '{update} {delete} ',
                        'buttons' => array()
                ),
        ),
)); ?>