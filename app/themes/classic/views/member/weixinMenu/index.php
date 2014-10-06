<?php
/* @var $this WeixinMenuController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
        '微信自定义菜单',
);
Yii::app()->clientScript->registerCoreScript('nestable');
?>

<!--注意：1级菜单最多只能开启3个，2级子菜单最多开启5个!

只有保存主菜单后才可以添加子菜单

生成自定义菜单,必须在已经保存的基础上进行,临时勾选启用点击生成是无效的! 第一步必须先修改保存状态！第二步点击生成!

当您为自定义菜单填写链接地址时请填写以"http://"开头，这样可以保证用户手机浏览的兼容性更好

撤销自定义菜单：撤销后，您的微信公众帐号上的自定义菜单将不存在；如果您想继续在微信公众帐号上使用自定义菜单，请点击“生成自定义菜单”按钮，将重新启用！
-->
<h1>微信菜单</h1>
<div class="btn-toolbar">
    <?php
    echo TbHtml::buttonGroup(array(
            array(
                    'label' => '添加菜单',
                    'color' => 'success',
                    'url' => array('create')
            ),
            array(
                    'label' => '同步菜单',
                    'color' => 'info', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                    'buttonType' => 'ajaxButton', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                    'ajaxOptions' => array(
                            'success' => 'js:function(data){alert(data.errmsg)}',
                            'dataType' => 'JSON',
                            'url' => $this->createUrl('sync'),
                    ),

            ),
            array(
                    'label' => '禁用菜单',
                    'color' => 'warning', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                    'buttonType' => 'ajaxButton', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                    'ajaxOptions' => array(
                            'success' => 'js:function(data){alert(data.errmsg)}',
                            'dataType' => 'JSON',
                            'url' => $this->createUrl('forbidden'),
                    ),

            )
    )); ?>
</div>


<div id="weixinMenu">
    <div class="dd">
        <ol class="dd-list">
            <?php
            $item = '';
            foreach ($menu as $key => $value) {

                $class = $value['status'] == 'Y' ? 'label-success' : 'label-important';

                if ($value && $value['children']) {

                    $subItem = '';
                    foreach ($value['children'] as $k => $v) {
                        $class = $v['status'] == 'Y' ? 'label-success' : 'label-important';
                        $subItem .= '
                    <li class="dd-item" data-id="' . $v['id'] . '">
                        <div class="dd-handle dd3-handle">Drag</div><div class="dd3-content">' .
                                CHtml::link($v['name'], $this->createUrl('update', array('id' => $v['id'])))
                                . ' <span class="label ' . $class . '">' . OutputHelper::getStatusEnabledDisableList($v['status']) . '</span>'
                                . '</div>

                    </li>
                    ';
                    }


                    $item .= '
                 <li class="dd-item" data-id="' . $value['id'] . '">
                    <div class="dd-handle dd3-handle">Drag</div><div class="dd3-content">' .
                            CHtml::link($value['name'], $this->createUrl('update', array('id' => $value['id'])))
                            . ' <span class="label ' . $class . '">' . OutputHelper::getStatusEnabledDisableList($value['status']) . '</span>'
                            . '</div>
                    <ol class="dd-list">
                        ' . $subItem . '
                    </ol>
                </li>
                ';
                } else {
                    $item .= '
                    <li class="dd-item" data-id="' . $value['id'] . '">
                        <div class="dd-handle dd3-handle">Drag</div>
                        <div class="dd3-content">' .
                            CHtml::link($value['name'] . ' <span class="label ' . $class . '">' . OutputHelper::getStatusEnabledDisableList($value['status']) . '</span>',
                                    $this->createUrl('update', array('id' => $value['id']))) . '
                        </div>
                    </li>
                    ';
                }
            }
            echo $item;
            ?>
        </ol>
    </div>


</div>

<script>
    $(document).ready(function () {


        $('#weixinMenu .dd').nestable({
            'maxDepth': 2
        });
        $('#weixinMenu .dd').on('change', function () {
            /* on change event */
            //console.log($('.dd').nestable('serialize'));

            $.post("<?php echo $this->createUrl('weixinMenu/sort');?>", {content: $('.dd').nestable('serialize')},
                    function (data) {
                        console.log(data);
                        if (!data['status']) {
                            alert(data['msg'] + ' ' + data['data']);
                        }
                    }, 'json'
            );

            if (window.JSON) {
                /*console.log(window.JSON.stringify($('.dd').nestable('serialize'))); //, null, 2));*/
            } else {
                output.val('JSON browser support required for this demo.');
            }

        });
    })
</script>