<header class="bar bar-nav">
    <header class="bar bar-nav">
        <?php echo CHtml::link('继续点餐', array('product/index'), array(
                'class' => 'btn btn-negative pull-right',
                'data-ignore' => 'push'
        )); ?>

        <h1 class="title">订单详情</h1>
    </header>
</header>


<div class="content">

    <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'feedback-form',
            'htmlOptions' => array(
                    'class' => 'input-group',
            ),
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => false,
    )); ?>

    <?php echo $form->errorSummary($model); ?>

    <ul class="table-view">
        <li class="table-view-divider">选择外送地址</li>
        <?php if ($addresses): ?>
            <?php $justOneAddress = count($addresses) == 1 ? true : false; ?>
            <?php foreach ($addresses as $address): ?>
                <li class="table-view-cell">
                    <?php echo $address->province, $address->city, $address->district, $address->address; ?>
                    <div class="toggle<?php if ($justOneAddress) {
                        echo ' active';
                    } ?>"
                         id="address_<?php echo $address->id; ?>"
                         data-group="address"
                         data-id="<?php echo $address->id; ?>">
                        <div class="toggle-handle"<?php if ($justOneAddress) {
                            echo ' style="-webkit-transform: translate3d(17px, 0px, 0px);"';
                        } ?>></div>
                    </div>
                </li>
            <?php endforeach; ?>

            <?php if ($justOneAddress) {
                echo $form->hiddenField($model, 'used_addresses_id', array(
                        'value' => $address->id,
                ));
            } else {
                echo $form->hiddenField($model, 'used_addresses_id');
            } ?>

            <li class="table-view-divider">更多地址</li>
            <li class="table-view-cell">
                <?php echo CHtml::link('
                <span class="media-object pull-left icon icon-home"></span>
                <div class="media-body">
                添加其他地址
                </div>
                ', array('profile/addAddress'), array(
                        'class' => 'navigate-right',
                        'data-ignore' => 'push'
                )); ?>
            </li>
        <?php else: ?>
            <li class="table-view-cell">
                <?php echo CHtml::link('
                <span class="media-object pull-left icon icon-home"></span>
                <div class="media-body">
                添加外送地址
                </div>
                ', array('profile/addAddress'), array(
                        'class' => 'navigate-right',
                        'data-ignore' => 'push'
                )); ?>
            </li>
        <?php endif; ?>


        <?php if ($payments): ?>
            <?php $justOnePayment = count($payments) == 1 ? true : false; ?>
            <li class="table-view-divider">支付方式</li>
            <?php foreach ($payments as $payment): ?>
                <li class="table-view-cell">
                    <?php echo $payment->paymentMethod->name; ?>
                    <div class="toggle<?php if ($justOnePayment) {
                        echo ' active';
                    } ?>"
                         id="address_<?php echo $payment->payment_method_id; ?>"
                         data-group="payment"
                         data-id="<?php echo $payment->payment_method_id; ?>">
                        <div class="toggle-handle"<?php if ($justOnePayment) {
                            echo ' style="-webkit-transform: translate3d(17px, 0px, 0px);"';
                        } ?>></div>
                    </div>
                </li>
            <?php endforeach; ?>

            <?php if ($justOnePayment) {

                echo $form->hiddenField($model, 'payment_method_id', array(
                        'value' => $payment->payment_method_id,
                ));
            } else {
                echo $form->hiddenField($model, 'payment_method_id');
            } ?>

        <?php endif; ?>
        <li class="table-view-divider">送餐时间</li>
        <div class="content-padded">
            <?php echo $form->dropDownList($model, 'delivery_time', OutputHelper::getDeliveryTimeList(), array('span' => 5, 'maxlength' => 45)); ?>
        </div>

        <li class="table-view-divider" for="ShopOrder_comment">特殊要求</li>
    </ul>
    <?php echo $form->textArea($model, 'comment', array('size' => 60, 'maxlength' => 1024,
            'placeholder' => '填写你订单留言',
        //'rows' => '3'
    )); ?>


    <div class="content-padded">
        <button class="btn btn-positive btn-block">提交订单</button>
    </div>

    <?php $this->endWidget(); ?>


</div>

<script>

    $(document).ready(function () {

        window.addEventListener('toggle', toggleEvent);

        $('body').delegate('.toggle', 'ontouchend', function (event) {
            toggleEvent(event);
        })


        //toggle-address
        function toggleEvent (event) {


            var obj = event.target;

            //alert($(obj).attr('data-id'));
            // console.log(obj);
            //console.log($(obj).attr('data-group'));

            var groups, group, data_id;
            group = $(obj).attr('data-group');
            data_id = $(obj).attr('data-id');

            //console.log('toggle组：', group);
            //console.log('隐藏ID：', data_id);

            groups = $("div[data-group='" + group + "']");
            $.each(groups, function (i, o) {
                //console.log('所有开关', o);
                if (obj != o) {
                    $(o).removeClass('active');
                    $(o).children('.toggle-handle').css('-webkit-transform', 'translate3d(0px, 0px, 0px)');
                }
            });


            //console.log('切换后值', data_id);

            //设置值
            if (group == 'address') {
                if (obj.className == 'toggle') {
                    $('#ShopOrder_used_addresses_id').val('');
                } else {
                    $('#ShopOrder_used_addresses_id').val(data_id);
                }
            } else if (group == 'payment') {
                if (obj.className == 'toggle') {
                    $('#ShopOrder_payment_method_id').val('');
                } else {
                    $('#ShopOrder_payment_method_id').val(data_id);
                }
            }


        }


        $('.toggle').delegate('.toggle-handle', 'click', function (event) {
            clickEvent(event);
        })

        /*$('body').delegate('.toggle-handle', 'ontouchend', function (event) {
         clickEvent(event);
         })*/


        function clickEvent (event) {

            var obj = event.target.parentNode;
            var groups, group, data_id;
            group = $(obj).attr('data-group');
            data_id = $(obj).attr('data-id');

            //console.log('toggle组：', group);
            //console.log('隐藏ID：', data_id);

            groups = $("div[data-group='" + group + "']");
            $.each(groups, function (i, o) {
                //console.log('所有开关', o);
                if (obj != o) {
                    $(o).removeClass('active');
                    $(o).children('.toggle-handle').css('-webkit-transform', 'translate3d(0px, 0px, 0px)');
                }
            });


            //console.log('切换后值', data_id);

            //设置值
            if (group == 'address') {
                if (obj.className == 'toggle') {
                    $('#ShopOrder_used_addresses_id').val('');
                } else {
                    $('#ShopOrder_used_addresses_id').val(data_id);
                }
            } else if (group == 'payment') {
                if (obj.className == 'toggle') {
                    $('#ShopOrder_payment_method_id').val('');
                } else {
                    $('#ShopOrder_payment_method_id').val(data_id);
                }
            }
        }
    })

</script>