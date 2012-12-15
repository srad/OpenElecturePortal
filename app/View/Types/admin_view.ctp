<div class="types view">
<h2><?php  echo __('Type'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($type['Type']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($type['Type']['name']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Type'), array('action' => 'edit', $type['Type']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Type'), array('action' => 'delete', $type['Type']['id']), null, __('Are you sure you want to delete # %s?', $type['Type']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Types'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Type'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Videos'), array('controller' => 'videos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Video'), array('controller' => 'videos', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Videos'); ?></h3>
	<?php if (!empty($type['Video'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Listing Id'); ?></th>
		<th><?php echo __('Video Id'); ?></th>
		<th><?php echo __('Provider Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Subtitle'); ?></th>
		<th><?php echo __('Speaker'); ?></th>
		<th><?php echo __('Location'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Url Thumbnail'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Updated'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($type['Video'] as $video): ?>
		<tr>
			<td><?php echo $video['id']; ?></td>
			<td><?php echo $video['lecture_id']; ?></td>
			<td><?php echo $video['video_id']; ?></td>
			<td><?php echo $video['provider_name']; ?></td>
			<td><?php echo $video['title']; ?></td>
			<td><?php echo $video['subtitle']; ?></td>
			<td><?php echo $video['speaker']; ?></td>
			<td><?php echo $video['location']; ?></td>
			<td><?php echo $video['description']; ?></td>
			<td><?php echo $video['url_thumbnail']; ?></td>
			<td><?php echo $video['created']; ?></td>
			<td><?php echo $video['updated']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'videos', 'action' => 'view', $video['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'videos', 'action' => 'edit', $video['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'videos', 'action' => 'delete', $video['id']), null, __('Are you sure you want to delete # %s?', $video['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Video'), array('controller' => 'videos', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
