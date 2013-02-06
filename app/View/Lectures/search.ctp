<div class="span11">
    <h1><?php echo __('Suchergebnisse für "%s"', isset($search) ? $search : __('nichts!')); ?></h1>

    <?php if (!isset($videos) || empty($videos)): ?>
    <div class="hero-unit">
        <?php echo __('Keine Veranstaltungen gefunden.'); ?>
    </div>
    <?php else: ?>
    <?php echo $this->element('video_row', $videos); ?>
    <?php endif; ?>
</div>