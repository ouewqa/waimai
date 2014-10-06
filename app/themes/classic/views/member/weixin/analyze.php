<?php
$this->breadcrumbs = array(
        '微信会员' => array('index'),
        '会员统计',
);
?>

<h1>会员统计</h1>
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
                        'title' => array('text' => OutputHelper::getShopOrderScopeList($scope) .'微信关注情况'),
                        'xAxis' => array(
                                'categories' => $subscribeData['day']
                        ),
                        'yAxis' => array(
                                'title' => array('text' => '新增会员')
                        ),
                        'series' => array(
                                array('name' => '新增人数', 'data' => $subscribeData['people']),

                        )
                )
        )
);

?>


