<?php if (isset($categoryList)): ?>
<div class="row">
    <div class="span3 balloon">
        <h4><?php echo $category['Category']['name']; ?></h4>

        <?php if (sizeof($categoryList) > 0): ?>
        <div id="accordion" class="triangle-right-blue top">
            <?php foreach ($categoryList as $lecture): ?>
                <?php if ($lecture['Lecture']['inactive']): ?>
                    <span class="accordion-header disabled"><a class="disabled"><?php echo $lecture['Lecture']['name']; ?></a></span>
                    <p></p>
                    <?php continue; ?>
                <?php else: ?>
                    <?php if ((isset($lecture['children']) && sizeof($lecture['children']) > 0) && ($lecture['Lecture']['dynamic_view'] == false)): ?>
                        <?php
                        $className = '';
                        foreach ($lecture['children'] as $child) {
                            if (isset($lecture_id) && $child['Lecture']['id'] == $lecture_id) {
                                $className = 'selected';
                                break;
                            }
                        }
                        ?>
                        <span class="accordion-header <?php echo $className; ?>"><a href=""><?php echo $lecture['Lecture']['name']; ?></a></span>
                    <?php else: ?>
                        <?php
                        $className = '';
                        if (isset($lecture_id)) {
                            if (intval($lecture['Lecture']['id']) == intval($lecture_id)) {
                                $className = 'selected';
                            }
                        }
                        if ($lecture['Lecture']['dynamic_view']) {
                            $className .= ' dynamic';
                        }
                        ?>
                        <span class="accordion-header <?php echo $className; ?>"><?php echo $this->Html->link($lecture['Lecture']['name'], '/lectures/view/' . $lecture['Lecture']['id'] . '/' . $category['Category']['id'] . '/' . (isset($term_id) ? $term_id : '')); ?></span>
                    <?php endif; ?>
                <div>
                    <?php
                    if (sizeof($lecture['children']) > 0) {
                        foreach ($lecture['children'] as $child) {
                            echo '<p><i class="icon-share-alt"></i> ' . $this->Html->link($child['Lecture']['name'], '/lectures/view/' . $child['Lecture']['id'] . '/' . $category['Category']['id'] . '/' . (isset($term_id) ? $term_id : ''), array('data-id' => $child['Lecture']['id'])) . '</p>';
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