<div class="span9">
    <?php if (empty($videos)): ?>
        <div class="hero-unit"><?php echo __('Keine Videos.'); ?></div>
    <?php else: ?>
        <?php $className = $isDynamicView ? 'dynamic-list' : ''; ?>

        <ul class="video-list <?php echo $className; ?>">
            <?php
            // Absolutely stupid, can't call here recursive function, todo: create a helper
            foreach ($videos as $video) {
                echo '<li>' . $this->element('video_row', array('depth' => 0, 'videos' => $video)) . '</li>';

                echo '<ul>';
                foreach ($video['children'] as $children) {
                    echo '<li>' . $this->element('video_row', array('depth' => 1, 'videos' => $children)) . '</li>';

                    echo '<ul>';
                    foreach ($children['children'] as $children2) {
                        echo '<li>' . $this->element('video_row', array('depth' => 2, 'videos' => $children2)) . '</li>';
                    }
                    echo '</ul>';
                }
                echo '</ul>';
            }
            ?>
        </ul>
    <?php endif; ?>
</div>

<?php if ($isDynamicView): ?>
<script>
    (function($) {

        var settings = {};

        var methods = {
            expand : function( options ) {
                // THIS
            },
            collapse : function( ) {
                // IS
            },
            toggle : function( ) {
                // GOOD
            }
        };

        $.fn.expandable = function(method, options) {
            settings = $.extend({
                'header-class'  : 'top',
                'content-class' : 'blue'
            }, options);

            // Method calling logic
            if ( methods[method] ) {
                return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
            } else if ( typeof method === 'object' || ! method ) {
                return methods.init.apply( this, arguments );
            } else {
                $.error( 'Method ' +  method + ' does not exist on jQuery' );
            }
        };

    })(jQuery);

    $(function () {
    });
</script>
<?php endif; ?>

<style>
    .video-list .ui-accordion-header {
        background: -moz-linear-gradient(center top, #FFFFFF 0%, #F6F6F6 47%, #EDEDED 100%) repeat scroll 0 0 transparent !important;
        border: 1px solid lightsteelblue;
        border-radius: 3px 3px 3px 3px;
        cursor: pointer;
        display: block;
        margin-top: 2px;
        padding: 9px !important;
        position: relative;
    }

    .video-list .ui-accordion-content {
        background: none repeat scroll 0 0 gainsboro;
        border-color: -moz-use-text-color lightsteelblue lightblue lightblue;
        border-image: none;
        border-style: none solid solid;
        border-width: medium 1px 1px;
        overflow: auto;
        padding: 10px 15px !important;
    }
</style>

<div class="sidebar span3">
    <?php if (!isset($terms)): ?>
    <?php echo $this->element('block_terms', $terms, $category, $listing_id); ?>
    <?php endif; ?>
    <?php echo $this->element('block_categories', $categoryList, $category, isset($listing_id) ? $listing_id : null, (isset($term_id) ? $term_id : null)); ?>
    <?php echo $this->element('block_links', $links); ?>
</div>