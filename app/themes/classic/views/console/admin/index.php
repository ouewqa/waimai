<?php
/* @var $this AdminController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
        '用户管理',
);
?>

    <h1>用户列表</h1>

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
                        'name' => 'username',
                        'type' => 'raw',
                        'value' => 'CHtml::link($data->username, $this->grid->controller->createUrl("update", array("id"=> $data->id)))',
                ),
                'role',
                'mobile',
                'count_sms',
                'count_email',
                array(
                        'name' => 'regist_time',
                        'value' => 'OutputHelper::timeFormat($data->regist_time)',
                ),
                array(
                        'name' => 'last_login_time',
                        'value' => 'OutputHelper::timeFormat($data->last_login_time)',
                ),
                array(
                        'name' => 'expire',
                        'value' => 'OutputHelper::timeFormat($data->expire)',
                ),
                array(
                        'class' => 'bootstrap.widgets.TbButtonColumn',
                        'template' => '{update} {auth} ',
                        'buttons' => array(
                                'auth' => array
                                (
                                        'label' => '权限',
                                        'icon' => 'icon-eye-open',
                                        'url' => 'Yii::app()->createUrl("/auth/assignment/view", array("id" => $data->id))',
                                        'htmlOptions' => array(
                                                'target' => '_blank',
                                        )
                                ),
                        )
                ),
        ),
)); ?>