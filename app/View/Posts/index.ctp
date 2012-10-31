<div class="span9">

    <div class="hero-unit" style="padding-bottom: 30px;">
        <h1><?php echo __('Willkommen auf dem eLecture Portal <br />der Goethe-Universität'); ?></h1>
        <br />
        <?php echo $this->Html->image('campus-westend-hz_small_low.jpg'); ?>
        <br />
    </div>

    <?php foreach($posts as $post): ?>
    <div class="hero-unit">
        <h4><?php echo $post['Post']['title']; ?></h4>
        <?php echo $post['User']['firstname'].' '.$post['User']['lastname']; ?> - <?php echo $post['Post']['created'] ?>
        <hr />
        <p class="content">
            <?php echo $post['Post']['content']; ?>
        </p>
    </div>
    <?php endforeach; ?>

</div>

<div class="sidebar span3">
    <?php echo $this->element('block_links', $links); ?>
</div>