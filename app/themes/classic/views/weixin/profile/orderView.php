<header class="bar bar-nav">
    <?php echo CHtml::link('<small>会员中心</small>', array('profile/index'), array(
            'class' => 'icon icon-left-nav pull-left',
            'data-transition' => 'slide-out',
        #'data-ignore' => 'push'
    )); ?>
    <?php



    #未受理，或已受理，但前5分钟内
    if ($model->status == 0 || (in_array($model->status, array(1)) && DATELINE - $model->dateline < 60 * 5)) {
        $btn = CHtml::link('取消订单', '#', array(
                'id' => 'btn_cancel_order',
                'class' => 'btn btn-negative pull-right',
                'data-id' => $model->id,
        ));
    } else if (in_array($model->status, array(1, 9, 10))) {
        $btn = CHtml::link('收到外卖？', '#', array(
                'id' => 'btn_complete_order',
                'class' => 'btn btn-negative pull-right',
                'data-id' => $model->id,
        ));
    } else {
        $btn = '';
    }


    echo $btn;


    ?>
    <h1 class="title">订单详情</h1>
</header>


<div class="content">
    <div class="card">
        <table id="table-cart">
            <thead>
            <tr>
                <td>名称</td>
                <td>数量</td>
                <td>价格</td>
            </tr>
            </thead>
            <tbody>
            <?php
            $total = $money = 0;
            foreach ($model->items as $key => $value):
                $total += $value->number;
                $money += $value->number * $value->price;
                ?>

                <tr>
                    <td><?php echo OutputHelper::strcut($value->shopDish->name, 8); ?></td>
                    <td><?php echo $value->number; ?></td>
                    <td><?php echo $value->price; ?></td>
                </tr>

            <?php endforeach; ?>

            <?php if ($this->shop->minimum_charge && $this->shop->express_fee && $money < $this->shop->minimum_charge):
                $money += $this->shop->express_fee;
                ?>

                <tr>
                    <td>送餐费</td>
                    <td>（<?php echo $this->shop->minimum_charge; ?>元起免）</td>
                    <td><?php echo $this->shop->express_fee; ?></td>
                </tr>

            <?php endif; ?>

            </tbody>
            <tfoot>
            <tr>
                <td>合计</td>
                <td><?php echo $total; ?></td>
                <td>¥ <?php echo $money; ?></td>
            </tr>
            </tfoot>
        </table>
    </div>

    <?php
    #下单后15分钟才可以催单，并且在12小时内
    if (time() - $model->dateline > 60 * 15 && time() - $model->dateline < 3600 * 8): ?>
        <?php if (in_array($model->status, array(0, 1))): ?>
            <div class="content-padded">
                <button class="btn btn-positive btn-block"
                        id="btn_cuidan" data-id="<?php echo $model->id; ?>">还没派送，立即催单！
                </button>
            </div>
        <?php elseif ($model->status == 9): ?>
            <div class="content-padded">
                <button class="btn btn-block">已经催促商家了</button>
            </div>
        <?php endif; ?>

    <?php endif; ?>

    <div class="card">
        <ul class="table-view">
            <li class="table-view-cell table-view-divider table-view-column">
                订单信息
            </li>
        </ul>
        <div class="content-padded color-normal">
            <p>订单编号：<?php echo $model->id; ?></p>

            <p>订单状态：<?php echo $model->status ? OutputHelper::getShopOrderStatusList($model->status) : '未受理'; ?></p>

            <p>付款方式：<?php echo $model->paymentMethod->name; ?></p>

            <p>下单时间：<?php echo OutputHelper::timeFormat($model->dateline, false, 'Y-m-d H:i:s'); ?></p>
        </div>
    </div>
    <div class="card">
        <ul class="table-view">
            <li class="table-view-cell table-view-divider table-view-column">送餐信息</li>

        </ul>

        <div class="content-padded color-normal">
            <p>地址：<?php echo $model->usedAddresses->district, $model->usedAddresses->address; ?></p>

            <p>时间：<?php echo $model->delivery_time; ?></p>

            <p>姓名：<?php echo $model->usedAddresses->realname; ?></p>

            <p>
                手机：<?php echo $model->usedAddresses->mobile; ?>
            </p>
            <?php if ($model->comment): ?>
                <p>订单留言：<?php echo $model->comment; ?></p>
            <?php endif; ?>
        </div>

    </div>


    <div class="card">
        <?php
        $contact = $this->shop->telephone ? $this->shop->telephone : $this->shop->mobile;
        ?>
        <div class="content-padded" style="text-align: center">
            如有问题，请拨打本店电话：<br /><a class="tel"
                                  gaevent="imt/detail/bizPhone"
                                  href="tel:<?php echo $contact; ?>"><?php echo $contact; ?></a>
        </div>
    </div>
