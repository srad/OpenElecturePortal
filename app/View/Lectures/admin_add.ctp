<div class="listings form">
<?php echo $this->Form->create('Lecture'); ?>
	<fieldset>
		<legend><?php echo __('Add Listing'); ?></legend>
	<?php
		echo $this->Form->input('category_id');
		echo $this->Form->input('term_id');
		echo $this->Form->input('provider_name');
		echo $this->Form->input('code');
		echo $this->Form->input('name');
		echo $this->Form->input('html');
		echo $this->Form->input('inactive');
		echo $this->Form->input('dynamic_view');
		echo $this->Form->input('position');
		echo $this->Form->input('parent_id');
		echo $this->Form->input('lft');
		echo $this->Form->input('rght');
		echo $this->Form->input('invert_sorting');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Listings'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Terms'), array('controller' => 'terms', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Term'), array('controller' => 'terms', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Listings'), array('controller' => 'lectures', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parent Listing'), array('controller' => 'lectures', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Videos'), array('controller' => 'videos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Video'), array('controller' => 'videos', 'action' => 'add')); ?> </li>
	</ul>
</div>
