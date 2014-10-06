<header class="bar bar-nav">
    <h1 class="title">会员中心</h1>
</header>

<?php $this->beginContent('//layouts/_bar_tab');
$this->endContent(); ?>

<div class="content">
    <div class="card">
        <ul class="table-view">

            <li class="table-view-cell table-view-divider">会员服务</li>
            <li class="table-view-cell media">
                <?php echo CHtml::link('
                    <span class="badge badge-inverted">' . $count_order . '</span>
                    <span class="media-object pull-left icon icon-list"></span>
                    <div class="media-body">
                        订单记录
                    </div>
                    ', array('profile/orderList'), array(
                                'class' => 'navigate-right',
                                'data-transition' => 'slide-in',
                            #'data-ignore' => 'push'
                        )); ?>
            </li>
            <li class="table-view-cell media">
                <?php echo CHtml::link('
                    <span class="badge badge-inverted">' . $count_feedback . '</span>
                    <span class="media-object pull-left icon icon-compose"></span>
                    <div class="media-body">
                        意见反馈
                    </div>
                    ', array('feedback/index'), array(
                                'class' => 'navigate-right',
                                'data-transition' => 'slide-in',
                            #'data-ignore' => 'push'
                        )); ?>
            </li>
            <!--<li class="table-view-cell media">
                <?php /*echo CHtml::link('
                    <span class="badge badge-inverted">' . $count_feedback . '</span>
                    <span class="media-object pull-left icon icon-home"></span>
                    <div class="media-body">
                        常用地址
                    </div>
                    ', array('profile/address'), array(
                                'class' => 'navigate-right',
                                'data-transition' => 'slide-in',
                            #'data-ignore' => 'push'
                        )); */
            ?>
            </li>-->
        </ul>
    </div>

    <div class="card">
        <ul class="table-view">

            <li class="table-view-cell table-view-divider"><?php echo $this->shop->name; ?></li>
            <li class="table-view-cell media">
                <div class="media-body">
                    <?php echo CHtml::link('店铺介绍',
                            $this->createUrl('view'),
                            array(
                                    'class' => 'navigate-right',
                                    'data-transition' => 'slide-in',
                            )
                    ); ?>
                </div>
            </li>
            <li class="table-view-cell media">
                <div class="media-body"><?php echo CHtml::link($this->shop->district . $this->shop->address,
                            'http://map.baidu.com/mobile/webapp/search/search/qt=s&wd=' . $this->shop->city . $this->shop->district . $this->shop->address . '/?third_party=uri_api',
                            array(
                                    'class' => 'navigate-right',
                                    'data-transition' => 'slide-in',
                            )
                    ); ?>
                </div>
            </li>
            <?php if ($this->shop->telephone) : ?>
                <li class="table-view-cell media">
                    <div class="media-body">
                        座机：<a href="tel:<?php echo $this->shop->telephone; ?>"><?php echo $this->shop->telephone; ?></a>
                    </div>

                </li>
            <?php endif; ?>
            <?php if ($this->shop->mobile) : ?>
                <li class="table-view-cell media">
                    <div class="media-body">
                        手机：<a href="tel:<?php echo $this->shop->mobile; ?>"><?php echo $this->shop->mobile; ?></a>
                    </div>
                </li>
            <?php endif; ?>



            <!--<li class="table-view-cell table-view-divider">关于</li>
            <li class="table-view-cell media">
                <a class="navigate-right">
                    <span class="media-object pull-left icon icon-pages"></span>

                    <div class="media-body">
                        联系：
                    </div>
                </a>
            </li>-->

        </ul>
    </div>

    <div class="card">
        <ul class="table-view">
            <li class="table-view-cell table-view-divider">
                <div class="media-body">
                    <?php echo CHtml::link('当前版本：' . Yii::app()->params['version'],
                            $this->createUrl('about'),
                            array(
                                    'class' => 'navigate-right',
                                    'data-transition' => 'slide-in',
                            )
                    ); ?>
                </div>
            </li>
        </ul>
    </div>
</div>


<?php $this->beginContent('//layouts/_shopping_cart');
$this->endContent(); ?>

<script>
    function pushEvent () {
        WeixinApi.ready(function (Api) {
            var srcList = [];
            $.each($(".content img"), function (i, item) {
                if (item.src) {
                    srcList.push(item.src);
                    $(item).click(function (e) {
                        Api.imagePreview(this.src, srcList);
                    });
                }
            });
        });
    }


    window.addEventListener('push', pushEvent);

    $(document).ready(function () {
        pushEvent();
    });

</script>