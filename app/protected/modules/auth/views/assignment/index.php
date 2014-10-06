<?php
/* @var $this AssignmentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
        '权限管理' => '/auth',
        Yii::t('AuthModule.main', 'Assignments'),
);
?>

<h1><?php echo Yii::t('AuthModule.main', 'Assignments'); ?></h1>

<div class="btn-toolbar">
    <?php echo TbHtml::buttonGroup($this->menu); ?>
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
                    $.fn.yiiGridView.update('grid', {
                        data: $(this).serialize()
                    });
                    return false;
                });"
    );
    $this->renderPartial('_search', array('model' => $model));
    ?>
</div>



<?php $this->widget(
        'bootstrap.widgets.TbGridView',
        array(
                'type' => 'striped hover',
                'dataProvider' => $dataProvider,
                'emptyText' => Yii::t('AuthModule.main', 'No assignments found.'),
                'template' => "{items}\n{pager}",
                'columns' => array(
                        array(
                                'header' => Yii::t('AuthModule.main', 'User'),
                                'class' => 'AuthAssignmentNameColumn',
                        ),
                        array(
                                'header' => Yii::t('AuthModule.main', 'Assigned items'),
                                'class' => 'AuthAssignmentItemsColumn',
                        ),
                        array(
                                'class' => 'AuthAssignmentViewColumn',
                        ),
                ),
        )
); ?>
