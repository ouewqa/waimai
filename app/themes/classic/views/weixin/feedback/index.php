<header class="bar bar-nav">
    <?php echo CHtml::link('<small>会员中心</small>', array('profile/index'), array(
            'class' => 'icon icon-left-nav pull-left',
            'data-transition' => 'slide-out',
            #'data-ignore' => 'push'
    )); ?>
    <?php echo CHtml::link('', array('feedback/create'), array(
            'class' => 'icon icon-compose pull-right',
            'data-transition' => 'slide-in',
            #'data-ignore' => 'push'
    )); ?>
    <h1 class="title">意见反馈</h1>
</header>

<div class="content">
    <?php if ($feedbacks): ?>
        <ul class="table-view">
            <?php foreach ($feedbacks as $feedback): ?>
                <li class="table-view-cell" style="padding: 10px">
                    <p class="pull-right"><?php echo OutputHelper::timeFormat($feedback->dateline); ?></p>
                    <?php echo $feedback->content; ?>


                    <?php if ($feedback->reply) { ?>
                        <blockquote>
                            <?php echo $feedback->reply; ?>
                            <p><?php echo OutputHelper::timeFormat($feedback->reply_time); ?></p>
                        </blockquote>
                    <?php } else { ?>
                        <blockquote>
                            管理员未回复
                        </blockquote>
                    <?php } ?>

                </li>
            <?php endforeach; ?>

        </ul>
    <?php else: ?>
    <div class="ajaxTips">点击右上角可提交意见反馈。</div>
        <?php endif; ?>

        <?php
        $this->widget('LinkPager', array(
                        'pages' => $pages,
                )
        );
        ?>
    </div>

