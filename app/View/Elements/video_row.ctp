<?php if (isset($videos['Listing']['name'])): ?>
<h3 class="video-header depth-<?php echo $depth; ?>">
    <?php echo $videos['Listing']['name']; ?>
</h3>
<?php endif; ?>

<?php $data = isset($videos['Video']) ? $videos['Video'] : $videos; ?>

<div class="video-content <?php echo (isset($depth) ? 'depth-' . $depth : ''); ?>">
    <?php if ((sizeof($data) > 0) && isset($videos['Listing'])): /* Skip this i.e. for search results. */ ?>
    <div class="row feed">
        <div class="pull-right">
            <i class="icon-search icon-bookmark"></i>
            <?php echo $this->Html->link(
                __('Diese Veranstaltung abonnieren (rss)'),
                array(
                    'controller' => 'listings',
                    'action' => 'view',
                    $videos['Listing']['id'],
                    $videos['Listing']['category_id'],
                    $videos['Listing']['term_id'],
                    'ext' => 'rss',
                )
            ); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php foreach ($data as $video):
        // Nest the video to have the same data structure as for "latest" videos
        if (!isset($video['Video'])) {
            $video['Video'] = $video;
        }
    ?>
    <div class="hero-unit video-row">

        <div class="row">

            <div class="span2">
                <ul class="thumbnails">
                    <li class="span2">
                        <div class="thumbnail">
                            <img alt="thumbnail" src="<?php echo $video['Video']['thumbnail_url']; ?>" />
                        </div>
                    </li>
                </ul>
            </div>

            <div class="span5">
                <ul class="video-details">
                    <?php if (!empty($video['Video']['title'])): ?>
                    <li class="title">
                        <h4><?php echo $video['Video']['title']; ?></h4>
                    </li>
                    <?php endif; ?>

                    <?php if (!empty($video['Video']['subtitle'])): ?>
                    <li class="subtitle"><span class="sub-header">
                        <?php echo __('Untertitel:'); ?></span> <?php echo $video['Video']['subtitle']; ?>
                    </li>
                    <?php endif; ?>

                    <?php if (!empty($video['Video']['speaker'])): ?>
                    <li class="speaker"><span class="sub-header">
                        <?php echo __('Sprecher:'); ?></span> <?php echo $video['Video']['speaker']; ?>
                    </li>
                    <?php endif; ?>

                    <?php if (!empty($video['Video']['location'])): ?>
                    <li class="location"><span class="sub-header">
                        <?php echo __('Ort:'); ?></span> <?php echo $video['Video']['location']; ?>
                    </li>
                    <?php endif; ?>

                    <?php if (!empty($video['Video']['video_date'])): ?>
                    <li class="event-date"><span class="sub-header">
                        <?php echo __('Datum:'); ?></span> <?php echo $this->Datetime->GetSimpleOrFullDate(date("Y-m-d H:i:s", strtotime($video['Video']['video_date']))); ?>
                    </li>
                    <?php endif; ?>

                    <?php if (!empty($video['Video']['description'])): ?>
                    <li class="description"><span class="sub-header">
                        <?php echo __('Beschreibung:'); ?></span> <?php echo $video['Video']['description']; ?>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="span1 pull-right">
                <div class="row">
                    <ul class="video-buttons">
                        <?php foreach ($video['Type'] as $type): ?>
                        <li>
                            <a target="_blank" href="<?php echo $type['VideosType']['url']; ?>"><span class="<?php echo $type['name'];?>"></span> <?php echo ucfirst($type['name']); ?></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

        </div>

    </div>
    <?php endforeach; ?>
</div>