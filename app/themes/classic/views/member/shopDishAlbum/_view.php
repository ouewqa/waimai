<?php
/* @var $this ShopDishAlbumController */
/* @var $data ShopDishAlbum */
?>
<li class="span2">
    <?php
    echo CHtml::image($data->image, '', array(
            'data-src' => 'holder.js/300x200',
    ));
    ?>
</li>