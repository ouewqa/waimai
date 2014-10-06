<header class="bar bar-nav">
    <?php echo CHtml::link('<small>会员中心</small>', array('profile/index'), array(
            'class' => 'icon icon-left-nav pull-left',
            'data-transition' => 'slide-out',
        #'data-ignore' => 'push'
    )); ?>
    <h1 class="title">关于微单 <?php echo Yii::app()->params['version']; ?></h1>
</header>


<div class="content">
    <div class="content-padded">
        <p>微单，一套专业的微信订餐解决方案。</p>

        <p>由微信在线订餐系统、云打印机、后台系统维护三部分构成。</p>

        <p><strong>微信订餐系统：</strong>提供订餐页面，简洁人性化的界面，让用户快速完成下单流程；</p>

        <p><strong>云打印机：</strong>提供订单提醒、自动打印、催单等通知功能；</p>

        <p><strong>后台系统：</strong>提供电子菜单生成，会员维护，销售报表等维护功能。</p>

       <blockquote>
           <p>
               微单，由深圳市菠柚科技有限公司于2014年被推出。当前版本为<?php echo Yii::app()->params['version']; ?>。
           </p>

           <p>
               我们主要的研发人员来自华为、腾讯、盛大等知名企业。我们是一群热爱生活, 憧憬美好未来的年轻人，我们用自己的激情去打造服务生活的联网电子产品，能够为用户带来更加贴心的智能生活方式。
           </p>

           <p>官方网址：<?php echo CHtml::link('http://www.bo-u.cn', 'http://www.bo-u.cn'); ?> </p>

           <p>
               联系电话：<?php echo CHtml::link(Yii::app()->params['telephone'], 'tel:' . Yii::app()->params['telephone']); ?> </p>

       </blockquote>

    </div>

</div>
