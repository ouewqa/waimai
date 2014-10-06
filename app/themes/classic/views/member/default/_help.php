<div class="accordion-group">
    <div class="accordion-heading">
        <a class="accordion-toggle"
           data-toggle="collapse"
           data-parent="#help_list .accordion"
           href="#help_<?php echo $data->id; ?>">
            <?php
            $i = $index + 1;
            echo $i, '、', $data->title;
            ?>
        </a>
    </div>
    <div id="help_<?php echo $data->id; ?>"
         class="accordion-body collapse">
        <div class="accordion-inner">
            <?php
            echo $data->content;
            ?>
        </div>
    </div>
</div>

