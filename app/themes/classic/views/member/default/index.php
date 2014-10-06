<?php
$this->breadcrumbs = array(
        '基本信息',
);
?>
<style>
    .dashboard-header {
        border-bottom: 4px solid #E7EAEC;
        padding: 20px 20px 20px 20px;
    }

    .analyze {
        list-style: none;

    }

    .analyze li {
        float: left;
        width: 25%;
        text-align: center;
        line-height: 28px;
    }

    .analyze li h3 {
        font-size: 22px;
    }
</style>

<div class="well well-small">
    <ul class="analyze">
        <li>
            <h3>账号类型</h3>

            <div>
                <?php if ($this->user->role == 'member') {
                    echo '普通用户';
                }; ?>
            </div>
        </li>
        <li>
            <h3>过期时间</h3>

            <div>
                <?php
                echo OutputHelper::timeFormat($this->user->expire);
                ?>
            </div>
        </li>

        <li>
            <h3>短信包剩余量</h3>

            <div>
                <?php echo $this->user->count_sms; ?>
            </div>
        </li>

        <li>
            <h3>本月请求数</h3>

            <div>
                <?php echo $count_request; ?>/20,000
            </div>
        </li>

    </ul>

    <div style="clear: both;"></div>

</div>

<?php $this->widget('bootstrap.widgets.TbTabs', array(
        'tabs' => array(
                array(
                        'label' => '系统通知',
                        'content' => $this->renderPartial(
                                        'notification',
                                        array(
                                                'model' => $notifications,
                                        ),
                                        true),
                        'active' => $type == 'notification' ? true : false,
                ),
                array(
                        'label' => '使用帮助',
                        'content' => $this->renderPartial(
                                        'help',
                                        array(
                                                'model' => $help,
                                        ),
                                        true),
                        'active' => $type == 'help' ? true : false
                ),

        ),
)); ?>


<div class="dashboard-header">
    <!--欢迎使用本微信订餐软件。
    <p>
        产品经理：Keen, 主程序员：Godfrey, 前端交互：Joyce
    </p>-->
</div>


