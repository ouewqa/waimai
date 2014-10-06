<?php
/* @var $this WeixinController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    '微信指令使用统计',
);
?>

<h1>指令使用统计</h1>
<div class="btn-toolbar pull-right">
    <?php
    $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'type' => 'info',
        'toggle' => 'radio', // 'checkbox' or 'radio'
        'buttons' => $data,
    ));
    ?>
</div>

<?php $this->widget('bootstrap.widgets.TbButton', array(
    'label' => '更新指令排序',
    'type' => 'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'buttonType' => 'ajaxButton', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'ajaxOptions' => array(
        'success' => 'js:function(data){alert(data.errmsg)}',
        'dataType' => 'JSON',
    ),
    'url' => $this->createUrl('commandLog', array('scope' => $scope)),
)); ?>


<?php
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'id' => 'page-grid-weixin',
    'type' => 'hover bordered ',
    'dataProvider' => $dataProvider,
    'columns' => array(
        //'id',

        //'weixin_command_id',
        array(
            'name' => '指令ID',
            'type' => 'raw',
            'value' => '$data->weixinCommand->id',
        ),

        array(
            'name' => 'weixin_command_id',
            'type' => 'raw',
            'value' => 'CHtml::link($data->weixinCommand->command, $this->grid->controller->createUrl("/console/weixinCommand/update", array("id"=> $data->weixinCommand->id)))',
        ),

            array(
                    'name' => '指令描述',
                    'type' => 'raw',
                    'value' => '$data->weixinCommand->description',
            ),

        array(
            'name' => '指令类型',
            'type' => 'raw',
            'value' =>'OutputHelper::getWeixinCommandTypeList($data->weixinCommand->type)',
        ),

        array(
            'name' => '使用频率',
            'value' => '$data->id',
        ),
    ),
));
?>
