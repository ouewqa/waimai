<header class="bar bar-nav">
    <?php echo CHtml::link('<small>会员中心</small>', array('profile/index'), array(
            'class' => 'icon icon-left-nav pull-left',
            'data-transition' => 'slide-out',
        #'data-ignore' => 'push'
    )); ?>
    <h1 class="title">订单列表</h1>
</header>


<div class="content">

    <?php if ($orders): ?>
        <ul class="table-view">
            <li class="table-view-divider">您在过去30天内购买的订单</li>
            <?php foreach ($orders as $order):

                /*$name = array();
                foreach ($order->shopDishes as $key => $value) {
                    $name[] = $value->name;
                }*/
                ?>
                <li class="table-view-cell">
                    <?php echo CHtml::link(
                            '<img class="media-object pull-left" src="' . ImageHelper::thumb($order->image, 100, 62) . '">
                             <div class="media-body">
                             <p>编号：' . $order->id . '</p>
                             <p>状态：' . ($order->status ? OutputHelper::getShopOrderStatusList($order->status) : '未受理') . '</p>
                             <p>时间：' . OutputHelper::timeFormat($order->dateline, true) . '</p>
                            </div>
                            ',
                            array('profile/orderView', 'id' => $order->id), array(
                                    'class' => 'navigate-right',
                                    'data-transition' => 'slide-in',
                            )
                    );
                    ?>


                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div class="ajaxTips">您当前暂无订单记录</div>
    <?php endif; ?>



    <?php
    $this->widget('LinkPager', array(
                    'pages' => $pages,
            )
    );
    ?>
</div>