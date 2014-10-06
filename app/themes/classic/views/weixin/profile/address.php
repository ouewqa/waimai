<header class="bar bar-nav">
    <?php echo CHtml::link('<small>上一页</small>', array('profile/index'), array(
            'class' => 'icon icon-left-nav pull-left',
            'data-transition' => 'slide-out',
        #'data-ignore' => 'push'
    )); ?>
    <?php echo CHtml::link('', array('profile/addAddress'), array(
            'class' => 'icon icon-plus pull-right',
            'data-transition' => 'slide-in',
        #'data-ignore' => 'push'
    )); ?>
    <h1 class="title">你的常用地址</h1>
</header>

<div class="content">


</div>
