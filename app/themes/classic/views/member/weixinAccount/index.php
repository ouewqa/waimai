<?php
/* @var $this WeixinAccountController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
        '微信公众账号',
);
?>

    <h1>微信公众账号</h1>
    <div class="control-group">

        <?php
        echo TbHtml::buttonGroup(array(
                array(
                        'label' => '添加微信公众账号',
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
                        'name' => 'type',
                        'type' => 'raw',
                        'value' => 'OutputHelper::getWeixinAccountTypeList($data->type)',
                ),

                array(
                        'name' => 'URL',
                        'value' => 'FileHelper::getPathAbsolute("/weixin/api/callBack/id/$data->id/token/$data->token")',
                ),
                array(
                        'name' => 'token',
                        'header' => 'Token',
                ),

                array(
                        'name' => 'advanced_interface',
                        'type' => 'raw',
                        'value' => 'OutputHelper::getWeixinAdvancedInterfaceList($data->advanced_interface)',
                ),

            /* array(
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