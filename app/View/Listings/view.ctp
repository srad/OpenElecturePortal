<div id="listings" class="span9">
    <?php if (empty($videos)): ?>
        <div class="hero-unit"><?php echo __('Keine Videos.'); ?></div>
    <?php else: ?>
        <?php
        $className = '';
        $tagType = 'ul';
        if ($isDynamicView) {
            $className = 'dynamic-list';
            $tagType = 'div';
        }
        ?>

        <<?php echo $tagType; ?> class="video-list <?php echo $className; ?>">
            <?php
            // todo: create a helper with recursive call
            foreach ($videos as $video) {
                echo (($isDynamicView) ? '' : '<li>'). $this->element('video_row', array('depth' => 0, 'videos' => $video)) . (($isDynamicView) ? '' : '</li>');

                if (sizeof($video['children']) > 0) {
                    echo '<'.$tagType.' class="child1  '.$className.'">';
                    foreach ($video['children'] as $children) {
                        echo (($isDynamicView) ? '' : '<li>') . $this->element('video_row', array('depth' => 1, 'videos' => $children)) . (($isDynamicView) ? '' : '</li>');

                        if (sizeof($children['children']) > 0) {
                            echo '<'.$tagType.' class="child2 '.$className.'">';
                            foreach ($children['children'] as $children2) {
                                echo (($isDynamicView) ? '' : '<li>') . $this->element('video_row', array('depth' => 2, 'videos' => $children2)) . (($isDynamicView) ? '' : '</li>');
                            }
                            echo '</'.$tagType.'>';
                        }
                    }
                    echo '</'.$tagType.'>';
                }
            }
            ?>
        </<?php echo $tagType; ?>>
    <?php endif; ?>
</div>

<?php if ($isDynamicView): ?>
<script>
$(function () {
    $('#listings .child1,#listings .child2').accordion({ heightStyle: 'content', collapsible: true });
});
</script>

<style>
.video-list .ui-accordion-header {
    background: #005eaa;
    background: -moz-linear-gradient(top,  #005eaa 70%, #d1d2d3 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(70%,#005eaa), color-stop(100%,#d1d2d3));
    background: -webkit-linear-gradient(top,  #005eaa 70%,#d1d2d3 100%);
    background: -o-linear-gradient(top,  #005eaa 70%,#d1d2d3 100%);
    background: -ms-linear-gradient(top,  #005eaa 70%,#d1d2d3 100%);
    background: linear-gradient(to bottom,  #005eaa 70%,#d1d2d3 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#005eaa', endColorstr='#d1d2d3',GradientType=0 );
    border: medium none;
    border-radius: 2px 2px 0 0;
    cursor: pointer;
    display: block;
    font-size: 16px;
    margin-top: 2px;
    padding: 7px 9px 15px !important;
    position: relative;
}
.video-list div.child1 span.ui-accordion-header-icon,
.video-list div.child2 span.ui-accordion-header-icon {
    display: none;
}

.video-list .ui-accordion-content {
    background: none repeat scroll 0 0 gainsboro;
    border-color: -moz-use-text-color lightsteelblue lightblue lightblue;
    border-image: none;
    border-style: none solid solid;
    border-width: medium 1px 1px;
    border-top:none;
    overflow: auto;
    padding: 10px 15px !important;
    margin-bottom: 10px;
}
</style>
<?php endif; ?>

<div class="sidebar span3">
    <?php if (isset($terms)): ?>
    <?php echo $this->element('block_terms', array($terms, $category, $listing_id), array('cache' => array('key' => 'block_terms', 'config' => 'view_long'))); ?>
    <?php endif; ?>
    <?php echo $this->element('block_categories', array($categoryList, $category, isset($listing_id) ? $listing_id : null, (isset($term_id) ? $term_id : null))); ?>
    <?php echo $this->element('block_links', array($links), array('cache' => array('key' => 'block_category', 'config' => 'view_long'))); ?>
</div>