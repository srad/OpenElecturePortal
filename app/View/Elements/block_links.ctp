<div class="row">
    <div class="span3 balloon">
        <h4><?php echo __('Informationen'); ?></h4>

        <div class="triangle-right top">
            <?php
            if (isset($links) && (sizeof($links) > 0)) {
                foreach ($links as $lecture) {
                    echo $this->Html->link($lecture['Post']['title'], '/posts/view/' . $lecture['Post']['id'] . '/' . $lecture['Post']['slug']);
                }
            }
            else {
                echo '<a>' . __('Keine Informationen') . '</a>';
            }
            ?>
        </div>
    </div>
</div>