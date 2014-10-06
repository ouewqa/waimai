<?php
/* @var $this SiteProductOrderController */
/* @var $model SiteProductOrder */
?>

<?php
$this->breadcrumbs = array(
        '增值服务' => array('/member/siteProduct/index'),
        $model->siteProduct->name,
);
?>

    <h1>订单详情</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
        'htmlOptions' => array(
                'class' => 'table table-striped table-condensed table-hover',
        ),
        'data' => $model,
        'attributes' => array(
                'id',
                'payment',
                'order_sn',
                'subject',
                'description',
                'money',
                array(
                        'name' => 'dateline',
                        'value' => OutputHelper::timeFormat($model->dateline),
                ),
                array(
                        'name' => 'status',
                        'value' => OutputHelper::getPaidStatusList($model->status),
                ),
        ),
)); ?>