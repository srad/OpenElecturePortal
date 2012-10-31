<div class="videosTypes view">
<h2><?php  echo __('Videos Type'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($videosType['VideosType']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Video'); ?></dt>
		<dd>
			<?php echo $this->Html->link($videosType['Video']['title'], array('controller' => 'videos', 'action' => 'view', $videosType['Video']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Type'); ?></dt>
		<dd>
			<?php echo $this->Html->link($videosType['Type']['name'], array('controller' => 'types', 'action' => 'view', $videosType['Type']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Url'); ?></dt>
		<dd>
			<?php echo h($videosType['VideosType']['url']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Videos Type'), array('action' => 'edit', $videosType['VideosType']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Videos Type'), array('action' => 'delete', $videosType['VideosType']['id']), null, __('Are you sure you want to delete # %s?', $videosType['VideosType']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Videos Types'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Videos Type'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Videos'), array('controller' => 'videos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Video'), array('controller' => 'videos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Types'), array('controller' => 'types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Type'), array('controller' => 'types', 'action' => 'add')); ?> </li>
	</ul>
</div>
