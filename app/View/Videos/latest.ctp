<div class="span9">
    <h1><?php echo __('Neue Videos'); ?></h1>

    <?php
    foreach ($videos as $video) {
        echo $this->element('video_row', array('videos' => $videos));
    };
    ?>
</div>

<div class="sidebar span3">
    <?php echo $this->element('block_links', $links); ?>
</div>