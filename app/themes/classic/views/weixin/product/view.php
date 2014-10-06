<header class="bar bar-nav">
    <a href="#productDetail"
       class="btn btn-negative pull-right">关闭窗口</a>
    <a class="btn_favorite icon <?php echo $favorite ? 'icon icon-star-filled' : 'icon-star'; ?> pull-left" data-id="<?php echo $product->id; ?>">
        <small>收藏</small>
    </a>

    <h1 class="title"><?php echo OutputHelper::strcut($product->name, 14); ?></h1>
</header>

<div class="content">
    <?php if ($product->image): ?>
        <img src="<?php echo ImageHelper::thumb($product->image, 320, 160); ?>">
    <?php endif; ?>

    <div class="content-padded">
        <?php echo ImageHelper::htmlImageAutoThumb($product->description); ?>
    </div>


    <?php if ($album): ?>
        <ul class="table-view">
            <li class="table-view-divider">以下图片由网友提供，左右滑动查看更多</li>
        </ul>

        <div class="slider" id="mySlider">
            <div class="slide-group">
                <?php foreach ($album as $key => $value): ?>
                    <div class="slide"><img src="<?php echo ImageHelper::thumb($value->image, 320, 160); ?>" />
                        <span class="slide-text">
                            <span class="icon icon-left-nav"></span>
                            可左右划动查看
                          </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>