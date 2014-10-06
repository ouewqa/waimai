<?php
/* @var $this WeixinMessageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
        '微信消息管理',
);
?>

<h1>消息管理</h1>

<!--<div class="btn-toolbar pull-right">
    <?php
/*    echo TbHtml::buttonGroup(array(
            array(
                    'type' => 'info',
                    'toggle' => 'radio', // 'checkbox' or 'radio'
                    'buttons' => $statusData,
            ),
            array(
                    'type' => 'default',
                    'toggle' => 'radio', // 'checkbox' or 'radio'
                    'buttons' => $typeData,
            ),

    )); */?>
</div>-->

<!--<button class="btn btn-small dropdown-toggle" data-toggle="collapse" data-target="#search-toggle">
    <i class="icon-search">&nbsp;</i>
    <?php /*echo CHtml::link(' 搜索 ', '#', array('class' => 'search-button')); */?>
    <span class="caret">&nbsp;</span>
</button>
<div id="search-toggle" class="collapse out search-form" style="margin-top: 20px;">
</div>-->
<?php $this->widget('bootstrap.widgets.TbGridView', array(
        'dataProvider' => $dataProvider,
        'type' => array(
                TbHtml::GRID_TYPE_STRIPED,
                TbHtml::GRID_TYPE_BORDERED,
                TbHtml::GRID_TYPE_HOVER
        ),
        'dataProvider' => $dataProvider,
        'columns' => array(
                'id',
                array(
                        'name' => 'message',
                        'type' => 'raw',
                        'value' => 'CHtml::link($data->message, $this->grid->controller->createUrl("create", array("weixin_id"=> $data->weixin_id)))',
                ),
                array(
                        'name' => 'wexin_id',
                        'value' => '$data->weixin->nickname ? $data->weixin->nickname : $data->weixin->id'
                ),
            /*array(
                    'name' => 'wexin_account_id',
                    'value' => '$data->weixinAccount->name'
            ),*/
                array(
                        'name' => 'type',
                        'value' => 'OutputHelper::getWeixinMessageTypeList($data->type)'
                ),
                array(
                        'name' => 'status',
                        'value' => 'OutputHelper::getWeixinMessageStatusList($data->status)'
                ),
                array(
                        'name' => 'dateline',
                        'value' => 'OutputHelper::timeFormat($data->dateline)',
                ),
                array(
                        'class' => 'bootstrap.widgets.TbButtonColumn',
                        'template' => '{reply}',
                        'buttons' => array
                        (
                                'reply' => array
                                (
                                        'label' => '回复',
                                        'icon' => 'icon-certificate',
                                    //'visible' => '($data->recommend != 1 && $data->status == "Y") ? true : false',
                                        'url' => 'Yii::app()->createUrl("console/weixinMessage/create", array("weixin_id" => $data->weixin_id))',
                                ),
                        ),
                ),
        ),
));
?>
