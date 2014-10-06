<?php
/* @var $this WeixinGroupController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
        '会员分组',
);
?>

    <h1>会员分组</h1>

    <div class="btn-toolbar">
        <?php
        echo TbHtml::buttonGroup(array(
                array(
                        'label' => '同步分组',
                        'color' => 'info', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                        'buttonType' => 'ajaxButton', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                        'ajaxOptions' => array(
                                'success' => 'js:function(data){alert(data.errmsg)}',
                                'dataType' => 'JSON',
                                'url' => $this->createUrl('sync'),
                        ),

                ),
        ));
        ?>
    </div>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
        'dataProvider' => $dataProvider,
        'type' => array(
                TbHtml::GRID_TYPE_STRIPED,
                TbHtml::GRID_TYPE_BORDERED,
                TbHtml::GRID_TYPE_HOVER
        ),
        'dataProvider' => $dataProvider,
        'columns' => array(
                'group_id',
                'name',
                'member_count',
        ),
));
?>