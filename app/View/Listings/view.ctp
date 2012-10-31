<div class="span9">
    <h1><?php echo $title_for_layout; ?></h1>

    <?php
    if (empty($videos)):
        echo '<div class="hero-unit">'.__('Keine Videos.').'</div>';
    else:
        echo $this->element('video_row', $videos);
    endif;
    ?>
</div>

<div class="sidebar span3">
    <?php echo $this->element('block_terms', $terms, $category, $listing_id); ?>
    <?php echo $this->element('block_categories', $categoryList, $category, isset($listing_id) ? $listing_id : null, $term_id); ?>
    <?php echo $this->element('block_links', $links); ?>
</div>