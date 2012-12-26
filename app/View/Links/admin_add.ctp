<div class="links form hero-unit span6">
<?php echo $this->Form->create('Link'); ?>
	<fieldset>
		<legend><?php echo __('Add Link'); ?></legend>
	<?php
		echo $this->Form->input('title');
		echo $this->Form->input('url');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>