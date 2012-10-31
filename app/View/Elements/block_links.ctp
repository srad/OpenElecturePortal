<div class="row">
    <div class="span3 balloon">
        <h4><?php echo __('Informationen'); ?></h4>

        <div class="triangle-right top">
            <?php
            if (isset($links) && (sizeof($links) > 0)) {
                foreach ($links as $listing) {
                    echo $this->Html->link($listing['Post']['title'], '/posts/view/' . $listing['Post']['id'] . '/' . $listing['Post']['slug']);
                }
            }
            else {
                echo '<a>' . __('Keine Informationen') . '</a>';
            }
            ?>
        </div>
    </div>
</div>