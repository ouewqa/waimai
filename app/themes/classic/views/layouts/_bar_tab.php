<?php
$route = Yii::app()->controller->id . '/' . $this->getAction()->getId();
?>

<nav class="bar bar-tab">
    <?php echo CHtml::link('
            <span class="icon icon-home"></span>
            <span class="tab-label">首页</span>
            ', array('default/index'), array(
            'class' => $route == 'default/index' ? 'tab-item active' : 'tab-item',
            'data-ignore' => 'push'
    )); ?>

    <?php echo CHtml::link('
            <span class="icon icon-list"></span>
            <span class="tab-label">菜单</span>
            ', array('product/index'), array(
            'class' => $route == 'product/index' ? 'tab-item active' : 'tab-item',
            'data-ignore' => 'push'
    )); ?>

    <?php echo CHtml::link('
            <span class="icon icon-pages"></span>
            <span class="tab-label">购物车 <span class="badge badge-inverted badge badge-inverted" id="shoppingCart_count"></span></span>
            ', '#shoppingCart',
            array(
                    'id' => 'btn_shoppingcart',
                    'class' => 'tab-item',
            )
    ); ?>


    <?php echo CHtml::link('
            <span class="icon icon-person"></span>
            <span class="tab-label">会员中心</span>
            ', array('profile/index'), array(
            'class' => $route == 'profile/index' ? 'tab-item active' : 'tab-item',
            'data-ignore' => 'push'
    )); ?>
</nav>

<script>
    var cart = new Cart({
        minimum_charge: <?php echo $this->shop->minimum_charge ? $this->shop->minimum_charge : 0;?>,
        express_fee: <?php echo $this->shop->express_fee ? $this->shop->express_fee : 0;?>,
        business_hours: {
            open: "<?php echo $this->shop->opening_time_start ? str_replace(':','.', $this->shop->opening_time_start) : 0;?>",
            close: "<?php echo $this->shop->opening_time_end ? str_replace(':','.', $this->shop->opening_time_end) : 0;?>"
        }
    });

    $(document).ready(function () {
        tabBarEvent();

        /*兼容iOS5.X下无法检测到push事件*/
        $('body').delegate('.control-item', 'touchend', function(){
            setTimeout(tabBarEvent, 1000);
        });

    });

    window.addEventListener('push', tabBarEvent);

    function tabBarEvent () {
        cart.init();
    }

</script>
