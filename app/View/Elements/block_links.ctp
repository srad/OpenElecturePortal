<div class="row">
    <div class="span3 balloon links">
        <h4><?php echo __('Informationen'); ?></h4>

        <div class="triangle-right top">
            <?php
            if (isset($links['Posts']) && (sizeof($links['Posts']) > 0)) {
                foreach ($links['Posts'] as $link) {
                    echo '<p>'.$this->Html->link($link['Post']['title'], '/posts/view/' . $link['Post']['id'] . '/' . $link['Post']['slug']).'</p>';
                }
            }
            if (isset($links['Links']) && (sizeof($links['Links']) > 0)) {
                foreach ($links['Links'] as $link) {
                    echo '<p>'.$this->Html->link($link['Link']['title'], $link['Link']['url']).'</p>';
                }
            }
            ?>
        </div>
    </div>
</div>