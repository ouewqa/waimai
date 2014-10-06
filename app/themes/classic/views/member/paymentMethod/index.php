<?php
/* @var $this PaymentMethodController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
        '支付设置',
);

$this->menu = array(
        array('label' => 'Create PaymentMethod', 'url' => array('create')),
        array('label' => 'Manage PaymentMethod', 'url' => array('admin')),
);
?>

<h1>支付设置</h1>




<?php
$items = array();

foreach ($model as $value) {
    #判断是否开通
    $config = PaymentConfig::model()->find(
            'weixin_account_id=:weixin_account_id AND payment_method_id=:payment_method_id AND `key`="status" AND `value`="Y"', array(
            ':weixin_account_id' => $this->account->id,
            ':payment_method_id' => $value->id,
    ));

    if ($config) {
        $button = TbHtml::button('设置', array(
                'color' => TbHtml::BUTTON_COLOR_INFO,
                'data-sign' => $value->sign,
        ));
    } else {

        $button = TbHtml::button('开通' . $value->name, array(
                'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'data-sign' => $value->sign,
        ));
    }


    $items[] = array(
            'label' => $value->name,
            'caption' => $value->description . $button,
    );
}
?>

<?php echo TbHtml::thumbnails($items, array('span' => 3)); ?>


<?php $this->widget('bootstrap.widgets.TbModal', array(
        'id' => 'modal',
        'header' => '',
        'content' => '',
        'footer' => array(
                TbHtml::button('保存', array('data-dismiss' => 'modal', 'color' => TbHtml::BUTTON_COLOR_PRIMARY)),
                TbHtml::button('Close', array('data-dismiss' => 'modal')),
        ),
)); ?>


<script>
    var sign;
    $(document).ready(function () {

        $("body").delegate("button", "click", function (event) {


            var that = event.target;

            console.log(that);

            sign = $(that).attr('data-sign');

            if (sign) {
                $('#modal').modal('toggle');
            }


        })

        $('#modal').on('show', function () {
            $.get("<?php echo $this->createUrl('view');?>", { sign: sign},
                    function (data) {
                        $('#modal').html(data);
                    });

        })

        $('#modal').on('hide', function () {
            location.reload();
        })
    })

</script>