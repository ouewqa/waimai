<header class="bar bar-nav">
    <?php echo CHtml::link('<small>会员中心</small>', array('profile/index'), array(
            'class' => 'icon icon-left-nav pull-left',
            'data-transition' => 'slide-out',
    )); ?>
    <h1 class="title">本店介绍</h1>
</header>

<div class="content">
    <div class="content-padded">
        <?php echo $model->description; ?>
    </div>
</div>

