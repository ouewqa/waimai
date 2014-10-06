<?php
/* @var $this WeixinCommandController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    '微信指令',
);
?>

<h1>微信指令</h1>
<div class="btn-toolbar pull-right">
    <?php
    $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'type' => 'info',
        'toggle' => 'radio', // 'checkbox' or 'radio'
        'buttons' => $data,
    ));
    ?>
</div>

<div class="btn-toolbar">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label' => '添加指令',
        'type' => 'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'url' => $this->createUrl('create'),
    )); ?>
</div>



<?php
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'id' => 'page-grid-weixin-command',
    'type' => 'hover bordered ',
    'dataProvider' => $dataProvider,
    'columns' => array(
        'id',
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => 'CHtml::link($data->command, $this->grid->controller->createUrl("update", array("id"=> $data->id)))',
        ),
        array(
            'name' => 'match',
            'value' => 'OutputHelper::getWeixinCommandMatchTypeList($data->match)',
        ),
        'description:html',
        array(
            'name' => 'type',
            'type' => 'raw',
            'value' => 'OutputHelper::getWeixinCommandTypeList($data->type)',
        ),
        'value',
        'cost',
        array(
            'name' => 'ob',
            'header' => '优先级（越大越优先）',
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
            'template' => '{update} ',
            'buttons' => array()
        ),
    ),
));
?>


<script>
    $('#page-grid-weixin-command input').blur(function () {
        $.post("<?php echo $this->createUrl('weixinCommand/update');?>/id/" + $(this).attr('data-id'), { 'WeixinCommand': {ob: $(this).attr('value')}, 'ajax': true },
                function (data) {
                    //alert("Data Loaded: " + data);
                });
    })
</script>