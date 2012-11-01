<?php echo $this->Form->create('User', array('class' => 'validate', 'action' => 'login', 'class' => 'form-signin')); ?>
<h2 class="form-signin-heading"><?php echo __('Bitte anmelden'); ?></h2>
<?php echo $this->Form->input('username', array('label' => false, 'required' => true, 'class' => 'input-block-level', 'placeholder' => __('Benutzername'))); ?>
<?php echo $this->Form->input('password', array('label' => false, 'type' => 'password', 'required' => true, 'class' => 'input-block-level', 'placeholder' => __('Passwort'))); ?>
<hr/>
<button class="btn btn-large btn-primary" type="submit"><?php echo __('Anmelden'); ?></button>
<?php echo $this->Form->end(); ?>


<style type="text/css">
    .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 90px auto;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
    }

    .form-signin .form-signin-heading,
    .form-signin .checkbox {
        margin-bottom: 10px;
    }

    .form-signin input[type="text"],
    .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
    }

</style>