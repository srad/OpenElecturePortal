<?php if (isset($terms) && $terms !== null): ?>
<div class="row">
    <div class="span3 balloon">
        <h4><?php echo __('Semester'); ?></h4>

        <div class="triangle-right top">
            <?php
            if ($this->request->params['controller'] == 'categories') {
                $term_switch = $this->Html->url(array('controller' => 'categories', 'action' => 'view', $category['Category']['id']));
            } elseif ($this->request->params['controller'] == 'listings') {
                $term_switch = $this->Html->url(array('controller' => 'listings', 'action' => 'view', $listing_id, $category['Category']['id']));
            }
            $term_switch .= '/';
            echo $this->Form->create(array('id' => 'formSearch', 'class' => 'form'));
            echo $this->Form->input('term_id', array('div' => false, 'class' => 'span2', 'default' => $term_id, 'onchange' => 'window.location=\'' . $term_switch . '\'+this.value', 'label' => false));
            echo $this->Form->end();
            ?>
        </div>
    </div>
</div>
<?php endif; ?>