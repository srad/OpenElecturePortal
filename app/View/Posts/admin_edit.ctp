<div class="span10">
    <div class="posts form hero-unit">
        <?php echo $this->Form->create('Post'); ?>
        <fieldset>
            <legend><?php echo __('Beitrag Editieren'); ?></legend>
            <?php
            echo $this->Form->input('title', array('requied' => true, 'label' => __('Titel'), 'class' => 'input-xxlarge'));
            echo $this->Form->input('content', array('requied' => true, 'rows' => 10, 'label' => __('Beitrag'), 'class' => 'input-xxlarge ckeditor'));
            ?>
            <br />
            <?php
            echo $this->Form->input('show_link', array('label' => __('Link in der Seitenleiste anzeigen'), 'checked' => true));
            echo $this->Form->input('show_frontpage', array('label' => __('Auf der Startseite anzeigen'), 'checked' => true));
            echo $this->Form->input('publish', array('label' => __('Beitrag veröffentlichen'), 'checked' => true));
            ?>
        </fieldset>

        <div class="form-actions">
            <input type="submit" value="Speichern" class="btn btn-primary">
        </div>
    </div>
</div>

<?php echo $this->Html->script('vendor/ckeditor/ckeditor'); ?>