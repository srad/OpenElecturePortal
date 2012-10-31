<?php if (isset($categoryList)): ?>
<div class="row">
    <div class="span3 balloon">
        <h4><?php echo $category['Category']['name']; ?></h4>

        <?php if (sizeof($categoryList) > 0): ?>
        <div id="accordion" class="triangle-right-blue top">
            <?php foreach ($categoryList['Listing'] as $listing): ?>

            <?php if (isset($listing['Children1']) && sizeof($listing['Children1']) > 0): ?>
                <?php
                $className = '';
                if (isset($listing_id)) {
                    $className = array_key_exists(intval($listing_id), $listing['Children1']) ? 'selected' : '';
                }
                ?>
                <span class="accordion-header <?php echo $className; ?>"><a href=""><?php echo $listing['name']; ?></a></span>
            <?php else: ?>
                <span class="accordion-header"><?php echo $this->Html->link($listing['name'], '/listings/view/' . $listing['id'] . '/' . $category['Category']['id'] . '/' . $term_id); ?></span>
            <?php endif; ?>
            <div>
                <?php
                if (sizeof($listing['Children1']) > 0) {
                    foreach ($listing['Children1'] as $child) {
                        echo '<p><i class="icon-share-alt"></i> ' . $this->Html->link($child['name'], '/listings/view/' . $child['id'] . '/' . $category['Category']['id'] . '/' . $term_id) . '</p>';
                    }
                }
                ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div id="accordion" class="triangle-right-blue top">
            <span class="accordion-header"><?php echo __('Keine Videos'); ?></h4></span>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>