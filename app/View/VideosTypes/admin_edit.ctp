<div class="videosTypes form">
<?php echo $this->Form->create('VideosType'); ?>
	<fieldset>
		<legend><?php echo __('Edit Videos Type'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('video_id');
		echo $this->Form->input('type_id');
		echo $this->Form->input('url');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('VideosType.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('VideosType.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Videos Types'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Videos'), array('controller' => 'videos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Video'), array('controller' => 'videos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Types'), array('controller' => 'types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Type'), array('controller' => 'types', 'action' => 'add')); ?> </li>
	</ul>
</div>
