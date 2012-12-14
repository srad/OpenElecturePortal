<div class="span11">
    <div class="hero-unit">
        <h4><?php echo $video['Post']['title']; ?></h4>
        <?php echo __('Erstellt am ') . date('d.m.Y', strtotime($video['Post']['created'])); ?>
        <hr />
        <p class="content">
            <?php echo $video['Post']['content']; ?>
        </p>
    </div>
</div>