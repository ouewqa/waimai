<?php
$this->widget('bootstrap.widgets.TbListView', array(
        'id' => 'help_list',
        'dataProvider' => $model,
        'itemView' => '_help',
        'viewData' => array(),
        'itemsCssClass' => 'accordion',
        'emptyText' => '',
));
?>