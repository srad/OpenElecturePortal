<div class="row-fluid">
    <div class="content-padding">
        <div class="links form hero-unit">
            <?php echo $this->Form->create('Link'); ?>
            <fieldset>
                <legend><?php echo __('Link anlegen'); ?></legend>
                <?php
                echo $this->Form->input('title');
                echo $this->Form->input('url');
                ?>
            </fieldset>
            <div class="form-actions">
                <input type="submit" value="Speichern" class="btn btn-primary">
            </div>
        </div>
    </div>
</div>