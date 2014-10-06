<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:wb="http://open.weibo.com/wb">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php
    Yii::app()->bootstrap->register();
    Yii::app()->clientScript->registerCoreScript('cookie');
    ?>
    <script type="text/javascript" src="/js/common.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css" />
</head>

<body>

<?php if (isset($this->navbar) && $this->navbar) {
    $this->widget('bootstrap.widgets.TbNavbar', $this->navbar);
}?>


<?php if (isset($this->leftMenu) && $this->leftMenu) {

    ?>
    <div class="container-fluid" style="margin-top: 41px; padding:0;">
        <div class="row-fluid">
            <div id="menuContainer">
                <?php

                $menus = array();
                $menus[] = '<div class="accordion affix" id="menuItem">';

                foreach ($this->leftMenu as $key => $value) {

                    if (isset($value['items']) && $value['items']) {

                        $items = array();
                        $items[] = '<ul>';
                        foreach ($value['items'] as $item) {


                            if (isset($item['url']) && is_array($item['url'])) {
                                $url = $item['url'];
                                $route = isset($url[0]) ? $url[0] : '';
                                $item['url'] = $this->createUrl($route, array_splice($url, 1));
                            }


                            $icon = isset($item['icon']) ? '<i class="' . $item['icon'] . '"></i> ' : '';

                            $active = isset($item['active']) && $item['active'] ? ' class="in"' : '';

                            $items[] = '
                            <li' . $active . '><a href="' . $item['url'] . '">' . $icon . $item['label'] . '</a></li>
                            ';
                        }
                        $items[] = '</ul>';

                        $active = isset($value['active']) && $value['active'] ? ' in' : '';
                        $icon = isset($value['icon']) ? '<i class="' . $value['icon'] . '"></i> ' : '';

                        $menus[] = '
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a class="accordion-toggle"
                                   data-toggle="collapse"
                                   data-parent="#menuItem"
                                   href="#collapse' . $key . '">
                                    ' . $icon . $value['label'] . '
                                </a>
                            </div>
                            <div id="collapse' . $key . '" class="accordion-body collapse' . $active . '">
                                <div class="accordion-inner">
                                    ' . implode('', $items) . '
                                </div>
                            </div>
                        </div>
                        ';
                    } else {

                        if (is_array($value['url'])) {
                            $url = $value['url'];
                            $route = isset($url[0]) ? $url[0] : '';
                            $value['url'] = $this->createUrl($route, array_splice($url, 1));
                        }

                        $icon = isset($value['icon']) ? '<i class="' . $value['icon'] . '"></i> ' : '';

                        $menus[] = '<div class="accordion-group">
                                        <div class="accordion-heading">
                                            <a class="accordion-toggle"
                                               href="' . $value['url'] . '">
                                                ' . $icon . $value['label'] . '
                                            </a>
                                        </div>
                                    </div>
                                    ';
                    }

                }

                $menus[] = '</div>';

                echo implode('', $menus);

                ?>


            </div>
            <div id="contentContainer">
                <?php
                if (isset($this->breadcrumbs) && $this->breadcrumbs) {
                    $this->widget('bootstrap.widgets.TbBreadcrumb', array(
                            'homeLabel' => '<i class="icon-home"></i> 会员中心',
                            'homeUrl' => $this->createUrl('/member'),
                            'links' => $this->breadcrumbs,
                            'htmlOptions' => array(
                                    'class' => 'affix'
                            )
                    ));
                }
                ?>
                <div class="wraper">
                    <?php echo $content; ?>
                </div>

            </div>
        </div>
    </div>

<?php
} else {
    ?>

    <div class="container-fluid" style="margin-top: 41px; ">
        <?php echo $content; ?>
        <div class="clear"></div>
    </div>


<?php

}
?>

<footer>
    欢迎使用微单系统，如有问题请联系客服，手机：15019467705，或邮件：admin@bo-u.cn
    <div id="51dotla" style="display:none;">
        <!--<script language="javascript" type="text/javascript" src="http://js.users.51.la/17154225.js"></script>-->
    </div>
</footer>
</body>
</html>
