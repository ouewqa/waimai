<?php
$this->widget('bootstrap.widgets.TbListView', array(
        'id' => 'notification_list',
        'dataProvider' => $model,
        'itemView' => '_notification',
        'viewData' => array(),
        'itemsCssClass' => 'accordion',
        'emptyText' => '',
));
?>