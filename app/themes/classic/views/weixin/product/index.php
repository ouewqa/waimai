<script src="/js/parabola.min.js"></script>
<header class="bar bar-nav">
    <!-- <a href="#shoppingCart"
        class="btn btn-outlined pull-right"
        id="btn_shoppingcart">
         购物车<span class="badge badge-inverted badge badge-inverted" id="shoppingCart_count">0</span>
     </a>-->
    <a href="#categoryPopover">
        <h1 class="title">
            <?php echo $category ? $category->name : '菜品分类'; ?>
            <?php if (count($categories) > 1): ?>
                <span class="icon icon-caret"></span>
            <?php endif; ?>
        </h1>
    </a>

</header>


<?php if (count($categories) > 1): ?>
    <div id="categoryPopover" class="popover">
        <header class="bar bar-nav">
            <h1 class="title">请选择分类</h1>
        </header>
        <ul class="table-view">
            <li class="table-view-cell<?php echo 0 == $category_id ? ' active' : ''; ?>">
                <?php echo CHtml::link('所有分类', array('product/index'), array(
                        'data-ignore' => 'push'
                )); ?>
            </li>
            <?php foreach ($categories as $value) : ?>
                <li class="table-view-cell<?php echo $value->id == $category_id ? ' active' : ''; ?>">
                    <?php echo CHtml::link($value->name, array('product/index', 'category_id' => $value->id), array(
                            'data-ignore' => 'push'
                    )); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>


<div class="bar bar-standard bar-header-secondary">
    <div class="segmented-control">
        <?php echo CHtml::link('推荐',
                array('product/index', 'category_id' => $category_id, 'sort' => 'recommend'),
                array(
                        'class' => 'control-item' . ($sort == 'recommend' ? ' active' : ''),
                    #'data-ignore' => 'push'
                )); ?>


        <?php echo CHtml::link('超值',
                array('product/index', 'category_id' => $category_id, 'sort' => 'price'),
                array(
                        'class' => 'control-item' . ($sort == 'price' ? ' active' : ''),
                    #'data-ignore' => 'push'
                )); ?>

        <?php echo CHtml::link('热卖',
                array('product/index', 'category_id' => $category_id, 'sort' => 'sales'),
                array(
                        'class' => 'control-item' . ($sort == 'sales' ? ' active' : ''),
                    #'data-ignore' => 'push'
                )); ?>
        <?php echo CHtml::link('收藏',
                array('product/index', 'category_id' => $category_id, 'sort' => 'favorite'),
                array(
                        'class' => 'control-item' . ($sort == 'favorite' ? ' active' : ''),
                    #'data-ignore' => 'push'
                )); ?>

    </div>
</div>

<?php $this->beginContent('//layouts/_bar_tab');
$this->endContent(); ?>

<div class="content">
    <?php if ($products): ?>
        <ul class="table-view">

            <?php if ($sort == 'favorite'): ?>

                <?php foreach ($products as $product): ?>
                    <li class="table-view-cell">


                        <?php echo CHtml::link('
                        <img id="productImage_' . $product->shopDish->id . '" class="media-object" src="' . ($product->shopDish->image ? ImageHelper::thumb($product->shopDish->image, 90, 60) : 'http://placehold.it/60x60') . '">
                        ',
                                '#productDetail', array(
                                        'class' => 'pull-left btn_product',
                                        'data-id' => $product->shopDish->id,
                                )
                        );
                        ?>
                        <div class="media-body" style="padding-left:10px;">
                            <p><?php echo OutputHelper::strcut($product->shopDish->name, 12, ''); ?></p>

                            <p><?php echo '¥ ', $product->shopDish->price; ?></p>

                            <?php if ($product->shopDish->count_sales): ?>
                                <p style="font-size: 11px; color: #666"><?php echo '已有 ', $product->shopDish->count_sales, ' 人购买'; ?></p>
                            <?php endif; ?>
                        </div>
                        <button class="btn btn-positive btn_order"
                                href="#"
                                data-id="<?php echo $product->shopDish->id; ?>"
                                data-price="<?php echo $product->shopDish->price; ?>"
                                data-name="<?php echo OutputHelper::strcut($product->shopDish->name, 10, ''); ?>"
                                data-image="<?php echo $product->shopDish->image; ?>">点餐
                        </button>


                    </li>
                <?php endforeach; ?>


            <?php else: ?>

                <?php foreach ($products as $product): ?>
                    <li class="table-view-cell">
                        <?php echo CHtml::link('
                        <img id="productImage_' . $product->id . '" class="media-object" src="' . ($product->image ? ImageHelper::thumb($product->image, 90, 60) : 'http://placehold.it/60x60') . '">
                        ',
                                '#productDetail', array(
                                        'class' => 'pull-left btn_product',
                                        'data-id' => $product->id,
                                )
                        );
                        ?>
                        <div class="media-body" style="padding-left:10px;">
                            <p><?php echo OutputHelper::strcut($product->name, 12, ''); ?></p>

                            <p><?php echo '¥ ', $product->price; ?></p>

                            <?php if ($product->count_sales): ?>
                                <p style="font-size: 11px; color: #666"><?php echo '已有 ', $product->count_sales, ' 人购买'; ?></p>
                            <?php endif; ?>
                        </div>
                        <button class="btn btn-positive btn_order"
                                href="#"
                                data-id="<?php echo $product->id; ?>"
                                data-price="<?php echo $product->price; ?>"
                                data-name="<?php echo OutputHelper::strcut($product->name, 10, ''); ?>"
                                data-image="<?php echo $product->image; ?>">点餐
                        </button>


                    </li>
                <?php endforeach; ?>

            <?php endif; ?>
        </ul>
    <?php else: ?>
        <?php if ($sort == 'favorite'): ?>
            <div class="ajaxTips">你还没收藏任何菜品！</div>
        <?php endif; ?>
    <?php endif; ?>



    <?php
    $this->widget('LinkPager', array(
                    'pages' => $pages,
            )
    );
    ?>


</div>


<?php $this->beginContent('//layouts/_shopping_cart');
$this->endContent(); ?>

<div id="productDetail" class="modal">

</div>


<script>

    window.addEventListener('push', pushEvent);

    $(document).ready(function () {
        pushEvent();
    })

    function pushEvent (event) {

        //console.log(event.detail.state.url);

        if (event !== undefined) {
            if (event.detail.state.url.indexOf("/weixin/product/index") >= 0) {
            }
        }


        /*添加到购物车*/
        $('body').delegate('.btn_order', 'click', function (event) {
            var id = $(this).attr('data-id'),
                    price = $(this).attr('data-price'),
                    name = $(this).attr('data-name'),
                    image = $(this).attr('data-image');

            cart.add(id, price, name, image);
        })


        var start = {};
        var touchMove = false;
        var distanceY = false;

        /*列表滑动*/
        $('body').delegate('.btn_product', 'touchstart', function (e) {
            e = e.originalEvent || e;
            touchMove = false;
            start = { pageX: e.touches[0].pageX, pageY: e.touches[0].pageY };

            //console.log('start:', start);
        })


        $('body').delegate('.btn_product', 'touchmove', function (e) {

            e = e.originalEvent || e;
            //console.log(e.touches);
            if (e.touches.length > 1) {
                return; // Exit if a pinch
            }

            var current = e.touches[0];

            e = e.originalEvent || e;

            if (e.touches.length > 1) {
                return; // Exit if a pinch
            }


            distanceY = current.pageY - start.pageY;

            //滑动距离小于10。则点击，触发modal
            if (Math.abs(distanceY) > 10) {
                touchMove = true;
            }

            //console.log('distanceY', distanceY);


        })

        //阻止事件
        $('body').delegate('.btn_product', 'touchend', function (e) {
            console.log(touchMove);
            if (!touchMove) {
                $('#productDetail').html('<div class="ajaxTips">数据加载中……</div>');

                var id = $(this).attr('data-id');

                console.log(id);

                $.get("<?php echo $this->createUrl('product/view');?>", { id: id },
                        function (data) {

                            console.log(data);
                            if (data) {
                                //填充数据
                                $('#productDetail').html(data);


                                //图片轮播
                                WeixinApi.ready(function (Api) {
                                    var srcList = [];
                                    $.each($("#productDetail .content img"), function (i, item) {
                                        if (item.src) {
                                            srcList.push(item.src);
                                            $(item).click(function (e) {
                                                Api.imagePreview(this.src, srcList);
                                            });
                                        }
                                    });
                                });
                            }
                        });
            } else {
                return false;
            }
        })


        //收藏事件
        $('body').delegate('.btn_favorite', 'touchend', function (e) {
            var that = this,
                    id = $(this).attr('data-id');
            $.get("<?php echo $this->createUrl('product/favorite');?>",
                    { id: id },
                    function (data) {
                        if (data.status) {
                            $(that).removeClass('icon-star-filled');
                            $(that).removeClass('icon-star');
                            $(that).addClass(data.class);


                        }
                    }, 'json'
            );
        });
    }


</script>