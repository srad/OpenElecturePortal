<div class="row-fluid">
    <div class="content-padding">
        <div class="hero-unit">
            <?php echo $this->Form->create('User', array('class' => 'form')); ?>
            <fieldset>
                <legend><?php echo __('Benutzer anlegen'); ?></legend>
                <?php
                echo $this->Form->input('group_id', array('label' => __('Gruppe'), 'class' => 'input-xlarge', 'required' => true));
                echo $this->Form->input('username', array('label' => 'Benutzername', 'class' => 'input-xlarge', 'required' => true));
                echo $this->Form->input('password', array('label' => __('Passwort'), 'class' => 'input-xlarge', 'required' => true));
                echo $this->Form->input('firstname', array('label' => __('Vorname'), 'class' => 'input-xlarge', 'required' => true));
                echo $this->Form->input('lastname', array('label' => __('Nachname'), 'class' => 'input-xlarge', 'required' => true));
                echo $this->Form->input('active', array('label' => __('Konto-Aktivieren'), 'class' => 'input-xlarge'));
                ?>
            </fieldset>

            <div class="form-actions">
                <input type="submit" class="btn btn-primary" value="<?php echo __('Speichern'); ?>"/>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

