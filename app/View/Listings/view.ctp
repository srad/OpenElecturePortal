<div id="listings" class="span9">
    <?php if (empty($videos)): ?>
        <div class="hero-unit"><?php echo __('Keine Videos.'); ?></div>
    <?php else: ?>
        <?php echo $this->Listing->renderVideoList($videos, $isDynamicView); ?>
    <?php endif; ?>
</div>

<?php if ($isDynamicView): ?>
    <script>
    $(function () {
        $('#listings .container-videolist').accordion({ heightStyle: 'content', collapsible: true });
    });
    </script>

    <style>
    #listings .container-videolist .ui-accordion .ui-accordion-header {
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

    #listings .container-videolist span.ui-accordion-header-icon {
        display: none;
    }

    #listings  .depth-1 { margin-left: 20px; }
    #listings  .depth-2 { margin-left: 40px; }

    #listings .container-videolist .video-content {
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