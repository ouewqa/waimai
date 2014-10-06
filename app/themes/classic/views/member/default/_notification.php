<div class="accordion-group">
    <div class="accordion-heading">
        <a class="accordion-toggle"
           data-toggle="collapse"
           data-parent="#notification_list .accordion"
           href="#notification_<?php echo $data->id; ?>">
            <?php
            echo '<span class="label label-info">' . OutputHelper::timeFormat($data->dateline) . '</span> ' . $data->title;
            ?>
        </a>
    </div>
    <div id="notification_<?php echo $data->id; ?>"
         class="accordion-body collapse <?php echo $index == 0 ? 'in' : ''; ?>">
        <div class="accordion-inner">
            <?php
            echo $data->content;
            ?>
        </div>
    </div>
</div>

