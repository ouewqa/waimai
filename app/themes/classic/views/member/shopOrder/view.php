<table class="table table-bordered table-hover ">
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
            <td><?php echo $value->shopDish->name; ?></td>
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
        <td style="text-align: center;">合计</td>
        <td><?php echo $total; ?></td>
        <td>¥ <?php echo $money; ?></td>
    </tr>

    <?php
    switch ($model->status) {
        case '0':
        case '9':
            $arr = array(
                    '1' => '设置为已受理',
                    '2' => '取消订单',
                    '10' => '设置为派送中',
            );
            break;
        case '1':

            $arr = array(
                    '10' => '设置为派送中',
                    '20' => '设置为已完成',
            );
            break;

        case '10':
            $arr = array(
                    '20' => '设置为已完成',
            );
            break;

        default:
            $arr = array();
    }


    if ($arr) {

        $items = array();
        foreach ($arr as $key => $value) {

            switch($key){
                case '1':

                    $color = TbHtml::BUTTON_COLOR_SUCCESS;
                    break;
                case '2':

                    $color = TbHtml::BUTTON_COLOR_DANGER;
                    break;

                case '10':
                    $color = TbHtml::BUTTON_COLOR_INFO;
                    break;

                case '20':
                    $color = TbHtml::BUTTON_COLOR_WARNING;
                    break;

                default:
                    $color = null;
            }

            $items[] = array(
                    'label' => $value,
                    'color' => $color,
                    'buttonType' => 'ajaxButton', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                    'htmlOptions' => array(
                            'class' => 'btn_set_status',
                            'data-id' => $model->id,
                            'data-status' => $key,
                    )
            );
        }
        echo '<tr><td colspan="3">' . TbHtml::buttonGroup($items) . '</td></tr>';
    }
    ?>

    </tfoot>
</table>


