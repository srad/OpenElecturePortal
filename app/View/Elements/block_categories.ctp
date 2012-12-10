<?php if (isset($categoryList)): ?>
<div class="row">
    <div class="span3 balloon">
        <h4><?php echo $category['Category']['name']; ?></h4>

        <?php if (sizeof($categoryList) > 0): ?>
        <div id="accordion" class="triangle-right-blue top">
            <?php foreach ($categoryList as $listing): ?>
                <?php if ($listing['Listing']['inactive']): ?>
                    <span class="accordion-header disabled"><a class="disabled"><?php echo $listing['Listing']['name']; ?></a></span>
                    <p></p>
                    <?php continue; ?>
                <?php else: ?>
                    <?php if ((isset($listing['children']) && sizeof($listing['children']) > 0) && ($listing['Listing']['dynamic_view'] == false)): ?>
                        <?php
                        $className = '';
                        foreach ($listing['children'] as $child) {
                            if (isset($listing_id) && $child['Listing']['id'] == $listing_id) {
                                $className = 'selected';
                                break;
                            }
                        }
                        ?>
                        <span class="accordion-header <?php echo $className; ?>"><a href=""><?php echo $listing['Listing']['name']; ?></a></span>
                    <?php else: ?>
                        <?php
                        $className = '';
                        if (isset($listing_id)) {
                            if (intval($listing['Listing']['id']) == intval($listing_id)) {
                                $className = 'selected';
                            }
                        }
                        ?>
                        <span class="accordion-header <?php echo $className; ?>"><?php echo $this->Html->link($listing['Listing']['name'], '/listings/view/' . $listing['Listing']['id'] . '/' . $category['Category']['id'] . '/' . (isset($term_id) ? $term_id : '')); ?></span>
                    <?php endif; ?>
                <div>
                    <?php
                    if (sizeof($listing['children']) > 0) {
                        foreach ($listing['children'] as $child) {
                            echo '<p><i class="icon-share-alt"></i> ' . $this->Html->link($child['Listing']['name'], '/listings/view/' . $child['Listing']['id'] . '/' . $category['Category']['id'] . '/' . (isset($term_id) ? $term_id : '')) . '</p>';
                        }
                    }
                    ?>
                </div>
                <?php endif; ?>
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