<div class="span11">
    <div class="hero-unit">
        <h4><?php echo $post['Post']['title']; ?></h4>
        <?php echo __('Erstellt am ') . date('d.m.Y', strtotime($post['Post']['created'])); ?>
        <hr />
        <p class="content">
            <?php echo $post['Post']['content']; ?>
        </p>
    </div>
</div>