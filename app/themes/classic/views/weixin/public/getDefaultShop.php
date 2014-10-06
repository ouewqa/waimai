<header class="bar bar-nav">
    <h1 class="title">请选择你附近的门店</h1>
</header>
<div class="content">
    <div class="card">
        <ul class="table-view">

            <?php
            foreach ($shops as $shop):?>
                <li class="table-view-cell"><?php echo CHtml::link($shop->name, array('/weixin/public/setDefaultShop', 'shop_id' => $shop->id), array(
                            'data-ignore' => 'push'
                    )); ?></li>
            <?php endforeach; ?>

        </ul>
    </div>
</div>