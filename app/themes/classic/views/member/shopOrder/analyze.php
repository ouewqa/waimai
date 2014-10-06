<?php
$this->breadcrumbs = array(
        '订单管理' => array('index'),
        '订单统计',
);

$this->menu = array(
        array('label' => 'Create ShopOrder', 'url' => array('create')),
        array('label' => 'Manage ShopOrder', 'url' => array('admin')),
);
?>

<h1>订单统计</h1>
<div class="btn-toolbar pull-left">
    <?php

    $items = array();
    foreach (OutputHelper::getShopOrderScopeList() as $key => $value) {
        $items[] = array(
                'label' => $value,
                'color' => $scope == $key ? TbHtml::BUTTON_COLOR_INFO : null,
                'url' => $this->createUrl('analyze', array(
                                'scope' => $key,
                        ))
        );
    }
    echo TbHtml::buttonGroup($items); ?>
</div>

<?php
$this->widget(
        'yiiwheels.widgets.highcharts.WhHighCharts',
        array(
                'pluginOptions' => array(
                        'chart' => array(
                                'type' => 'spline'
                        ),
                        'title' => array('text' => OutputHelper::getShopOrderScopeList($scope) . '订单销量情况'),
                        'xAxis' => array(
                                'categories' => $orderData['day']
                        ),
                        'yAxis' => array(
                                'title' => array('text' => '销量金额'),
                        ),
                        'series' => array(
                                array('name' => '订单数量', 'data' => $orderData['order']),
                                array('name' => '总金额', 'data' => $orderData['money']),

                        ),
                    #连线
                        'tooltip' => array(
                                'crosshairs' => true,
                                'shared' => true,
                        ),
                )
        )
);
?>
