<?php foreach ($videos as $video): ?>
<div class="hero-unit video-row">

    <div class="row">

        <div class="span2">
            <ul class="thumbnails">
                <li class="span2">
                    <div class="thumbnail">
                        <?php echo '<img src="data:'.$video['Video']['thumbnail_mime_type'].';base64,' . base64_encode($video['Video']['thumbnail']) . '" />'; ?>
                    </div>
                </li>
            </ul>
        </div>

        <div class="span5">
            <ul class="video-details">
                <?php if (!empty($video['Video']['title'])): ?>
                <li class="title"><h4><?php echo $video['Video']['title']; ?></h4></li>
                <?php endif; ?>

                <?php if (!empty($video['Video']['subtitle'])): ?>
                <li class="subtitle"><span class="sub-header"><?php echo __('Untertitel:'); ?></span> <?php echo $video['Video']['subtitle']; ?></li>
                <?php endif; ?>

                <?php if (!empty($video['Video']['speaker'])): ?>
                <li class="speaker"><span class="sub-header"><?php echo __('Sprecher:'); ?></span> <?php echo $video['Video']['speaker']; ?></li>
                <?php endif; ?>

                <?php if (!empty($video['Video']['location'])): ?>
                <li class="location"><span class="sub-header"><?php echo __('Ort:'); ?></span> <?php echo $video['Video']['location']; ?></li>
                <?php endif; ?>

                <?php if (!empty($video['Video']['video_date'])): ?>
                <li class="event-date"><span class="sub-header"><?php echo __('Datum:'); ?></span> <?php echo $this->Datetime->GetSimpleOrFullDate(date("Y-m-d H:i:s", strtotime($video['Video']['video_date']))); ?></li>
                <?php endif; ?>

                <?php if (!empty($video['Video']['description'])): ?>
                <li class="description"><span class="sub-header"><?php echo __('Beschreibung:'); ?></span> <?php echo $video['Video']['description']; ?></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="span1 pull-right">
            <div class="row">
                <ul class="video-buttons">
                    <?php foreach ($video['Type'] as $type): ?>
                    <li>
                        <a href="<?php echo $type['VideosType']['url']; ?>"><span class="<?php echo $type['name'];?>"></span> <?php echo ucfirst($type['name']); ?>
                        </a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

    </div>

    <!--
    <hr />
    <div class="row meta">
        <div class="span8">
            <a><span class="label label-warning"><?php echo __('Später schauen'); ?></span></a>
        </div>
    </div>
    -->
</div>
<?php endforeach; ?>

<style>
.video-row hr {
    border: 1px dotted white;
    margin: 0 0 8px;
}
.video-row .meta {
    padding-bottom: 6px;
}
</style>