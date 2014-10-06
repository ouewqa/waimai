<?php
/* @var $this WeixinController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
        '微信会员',
);
?>

    <h1>微信会员</h1>

    <div class="btn-toolbar pull-right">
        <?php

        $items = array();
        foreach (OutputHelper::getWeixinMemberStatuslist() as $key => $value) {
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

    <div class="btn-toolbar">

        <?php /*$this->widget('bootstrap.widgets.TbButton', array(
                'label' => '同步会员列表',
                'type' => 'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'buttonType' => 'ajaxButton', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'ajaxOptions' => array(
                        'success' => 'js:function(data){alert(data.msg)}',
                        'dataType' => 'JSON',
                ),
                'url' => $this->createUrl('sync'),
        )); */
        ?>

        <!-- <?php /*$this->widget('bootstrap.widgets.TbButton', array(
                'label' => '同步会员资料',
                'type' => 'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'url' => $this->createUrl('syncUserInfo'),
        )); */
        ?>

        --><?php /*$this->widget('bootstrap.widgets.TbButton', array(
                'label' => '发送包',
                'type' => 'danger', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'url' => $this->createUrl('redPacket'),
        )); */
        ?>

    </div>

    <button class="btn btn-small dropdown-toggle" data-toggle="collapse" data-target="#search-toggle">
        <i class="icon-search">&nbsp;</i>
        <?php echo CHtml::link(' 搜索 ', '#', array('class' => 'search-button')); ?>
        <span class="caret">&nbsp;</span>
    </button>
    <div id="search-toggle" class="collapse out search-form" style="margin-top: 20px;">
        <?php
        Yii::app()->clientScript->registerScript(
                'search', "
                $('.search-form form').submit(function() {
                    $.fn.yiiGridView.update('page-grid-weixin', {
                        data: $(this).serialize()
                    });
                    return false;
                });"
        );
        $this->renderPartial('_search', array('model' => $model));
        ?>
    </div>


<?php $this->widget('bootstrap.widgets.TbGridView', array(
        'id' => 'page-grid-weixin',
        'dataProvider' => $dataProvider,
        'type' => array(
                TbHtml::GRID_TYPE_STRIPED,
                TbHtml::GRID_TYPE_BORDERED,
                TbHtml::GRID_TYPE_HOVER
        ),
        'dataProvider' => $dataProvider,
    //'filter' => $dataProvider->model,
        'columns' => array(
                'id',
                array(
                        'name' => 'nickname',
                        'type' => 'raw',
                        'value' => 'CHtml::link($data->nickname, $this->grid->controller->createUrl("update", array("id"=> $data->id)))',
                ),
                'realname',
                array(
                        'name' => 'weixin_group_id',
                        'value' => '$data->getGroupName($data->weixin_group_id)',
                ),
                'qq',
                'email',
                'city',
                array(
                        'name' => 'birthday',
                        'value' => 'OutputHelper::timeFormat($data->birthday)',
                ),

                array(
                        'name' => 'dateline',
                        'value' => 'OutputHelper::timeFormat($data->dateline)',
                ),
                array(
                        'name' => 'updatetime',
                        'value' => 'OutputHelper::timeFormat($data->updatetime)',
                ),
                array(
                        'class' => 'bootstrap.widgets.TbButtonColumn',
                        'template' => '{update}',
                        'buttons' => array
                        (),
                ),
        ),
));
?>