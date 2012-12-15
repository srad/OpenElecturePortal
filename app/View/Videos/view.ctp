<div class="videos view">
<h2><?php  echo __('Video'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($video['Video']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Lecture'); ?></dt>
		<dd>
			<?php echo $this->Html->link($video['Lecture']['name'], array('controller' => 'lectures', 'action' => 'view', $video['Lecture']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Video'); ?></dt>
		<dd>
			<?php echo $this->Html->link($video['Video']['title'], array('controller' => 'videos', 'action' => 'view', $video['Video']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($video['Video']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subtitle'); ?></dt>
		<dd>
			<?php echo h($video['Video']['subtitle']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Speaker'); ?></dt>
		<dd>
			<?php echo h($video['Video']['speaker']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Location'); ?></dt>
		<dd>
			<?php echo h($video['Video']['location']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($video['Video']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Url Thumbnail'); ?></dt>
		<dd>
			<?php echo h($video['Video']['url_thumbnail']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($video['Video']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Updated'); ?></dt>
		<dd>
			<?php echo h($video['Video']['updated']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Video'), array('action' => 'edit', $video['Video']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Video'), array('action' => 'delete', $video['Video']['id']), null, __('Are you sure you want to delete # %s?', $video['Video']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Videos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Video'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Listings'), array('controller' => 'lectures', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Listing'), array('controller' => 'lectures', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Videos'), array('controller' => 'videos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Video'), array('controller' => 'videos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Providers'), array('controller' => 'providers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Provider'), array('controller' => 'providers', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Types'), array('controller' => 'types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Type'), array('controller' => 'types', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Videos'); ?></h3>
	<?php if (!empty($video['Video'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Listing Id'); ?></th>
		<th><?php echo __('Video Id'); ?></th>
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
		foreach ($video['Video'] as $video): ?>
		<tr>
			<td><?php echo $video['id']; ?></td>
			<td><?php echo $video['lecture_id']; ?></td>
			<td><?php echo $video['video_id']; ?></td>
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
<div class="related">
	<h3><?php echo __('Related Types'); ?></h3>
	<?php if (!empty($video['Type'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($video['Type'] as $type): ?>
		<tr>
			<td><?php echo $type['id']; ?></td>
			<td><?php echo $type['name']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'types', 'action' => 'view', $type['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'types', 'action' => 'edit', $type['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'types', 'action' => 'delete', $type['id']), null, __('Are you sure you want to delete # %s?', $type['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Type'), array('controller' => 'types', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
