<div class="span9">

    <div class="hero-unit" style="padding-bottom: 30px;">
        <h1><?php echo __('Willkommen auf dem eLecture Portal <br />der Goethe-Universität'); ?></h1>
        <?php echo $this->Html->image('campus-westend-hz_small_low.jpg'); ?>
    </div>

    <?php foreach($posts as $lecture): ?>
    <div class="hero-unit">
        <h4><?php echo $lecture['Post']['title']; ?></h4>
        <?php echo __('Erstellt am ') . date('d.m.Y', strtotime($lecture['Post']['created'])); ?>
        <hr />
        <p class="content">
            <?php echo $lecture['Post']['content']; ?>
        </p>
    </div>
    <?php endforeach; ?>

</div>

<div class="sidebar span3">
    <?php echo $this->element('block_links', $links); ?>
</div>