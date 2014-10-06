<?php
/**
 * Created by PhpStorm.
 * User: Keen
 * Date: 14-8-9
 * Time: 上午9:32
 * To change this template use File | Settings | File Templates.
 */
?>
<?php if ($products): ?>
    <ul class="table-view">
        <li class="table-view-cell table-view-divider">Theaters nearby</li>
        <?php foreach ($products as $product) : ?>
            <li class="table-view-cell">
                <?php echo OutputHelper::strcut($product->name, 10); ?>
                <p>N：<?php echo $cart[$product->id]; ?> P:<?php echo $product->price; ?></p>
                <a class="btn btn-outlined btn-positive" href="#">Buy Tickets</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>