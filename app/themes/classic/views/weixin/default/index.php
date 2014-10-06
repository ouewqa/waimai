<header class="bar bar-nav">
    <a href="#myPopover">
        <h1 class="title">
            <?php echo $this->shop->name; ?>
            <?php if (count($shops) > 1): ?>
                <span class="icon icon-caret"></span>
            <?php endif; ?>
        </h1>
    </a>
</header>

<?php if (count($shops) > 1): ?>
    <div id="myPopover" class="popover">
        <header class="bar bar-nav">
            <h1 class="title">切换门店</h1>
        </header>
        <ul class="table-view">
            <?php
            foreach ($shops as $shop):
                ?>
                <li class="table-view-cell<?php echo $shop->id == $this->shop->id ? ' active' : ''; ?>">
                    <?php echo CHtml::link($shop->name, array('public/setDefaultShop', 'shop_id' => $shop->id), array(#'data-ignore' => 'push'
            )); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php $this->beginContent('//layouts/_bar_tab');
$this->endContent(); ?>

<div class="content">
    <?php if ($announcement): ?>
        <div class="content-padded">
            <?php
            echo ImageHelper::htmlImageAutoThumb($announcement->content);
            ?>
        </div>
    <?php endif; ?>
</div>

<?php $this->beginContent('//layouts/_shopping_cart');
$this->endContent(); ?>