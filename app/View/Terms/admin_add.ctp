<div class="terms form">
<?php echo $this->Form->create('Term'); ?>
	<fieldset>
		<legend><?php echo __('Add Term'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('order');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Terms'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Listings'), array('controller' => 'lectures', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Listing'), array('controller' => 'lectures', 'action' => 'add')); ?> </li>
	</ul>
</div>
