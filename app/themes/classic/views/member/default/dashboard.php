<style>
    .dashboard-header {
        border-bottom: 4px solid #E7EAEC;
        padding: 20px 20px 20px 20px;
    }

    .analyze {
        list-style: none;

    }

    .analyze li {
        float: left;
        width: 25%;
        text-align: center;
        line-height: 28px;
    }

    .analyze li h3 {
        font-size: 22px;
    }
</style>

<div class="well well-small">
    <ul class="analyze">
        <li>
            <h3>账号类型</h3>

            <div>
                <?php if ($this->user->role == 'member') {
                    echo '普通用户';
                }; ?>
            </div>
        </li>
        <li>
            <h3>过期时间</h3>

            <div>
                <?php
                echo OutputHelper::timeFormat($this->user->expire);
                ?>
            </div>
        </li>

        <li>
            <h3>短信包剩余量</h3>

            <div>
                <?php echo $this->user->count_sms; ?>
            </div>
        </li>

        <li>
            <h3>本月请求数</h3>

            <div>
                <?php echo $count_request; ?>/20,000
            </div>
        </li>

    </ul>

    <div style="clear: both;"></div>

</div>


<?php
$this->widget(
        'yiiwheels.widgets.highcharts.WhHighCharts',
        array(
                'pluginOptions' => array(
                        'chart' => array(
                                'type' => 'spline'
                        ),
                        'title' => array('text' => '本月订单销量情况'),
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
                                'valueSuffix' => '元',
                        ),
                )
        )
);
?>


<?php
$this->widget(
        'yiiwheels.widgets.highcharts.WhHighCharts',
        array(
                'pluginOptions' => array(
                        'title' => array('text' => '本月微信关注情况'),
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