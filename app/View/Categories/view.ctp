<div class="span9">
    <h1><?php echo $category['Category']['name']; ?></h1>

    <?php echo $this->element('choose_course'); ?>
</div>

<div class="sidebar span3">
    <?php echo $this->element('block_terms', array(
        'terms'      => (isset($terms) ? $terms : null),
        'category'   => $category,
        'lecture_id' => (isset($lecture_id) ? $lecture_id : null)
    )); ?>
    <?php echo $this->element('block_categories', $categoryList, $category); ?>
    <?php echo $this->element('block_links', $links); ?>
</div>