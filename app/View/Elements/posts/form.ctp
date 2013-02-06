<div class="row-fluid">
    <div class="content-padding">
        <div class="posts form hero-unit">
            <?php echo $this->Form->create('Post'); ?>
            <fieldset>
                <legend><?php echo $title; ?></legend>
                <?php
                echo $this->Form->input('title', array('required' => true, 'label' => __('Titel'), 'class' => 'input-xxlarge'));
                echo $this->Form->input('content', array('rows' => 10, 'label' => __('Beitrag'), 'class' => 'input-xxlarge ckeditor'));
                ?>
                <br/>
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
</div>

<?php echo $this->Html->script('vendor/ckeditor/ckeditor'); ?>