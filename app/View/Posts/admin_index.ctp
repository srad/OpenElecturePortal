<div class="row-fluid">
    <div class="content-padding">
        <div class="hero-unit posts index">
            <h2><?php echo __('Posts'); ?></h2>
            <table class="table table-bordered table-striped table-condensed">
                <tr>
                    <th><?php echo $this->Paginator->sort('title'); ?></th>
                    <th><?php echo $this->Paginator->sort('publish'); ?></th>
                    <th><?php echo $this->Paginator->sort('show_link'); ?></th>
                    <th><?php echo $this->Paginator->sort('show_frontpage'); ?></th>
                    <th><?php echo $this->Paginator->sort('created'); ?></th>
                    <th class="actions"><?php echo __('Actions'); ?></th>
                </tr>
                <?php
                foreach ($posts as $post): ?>
                    <tr>
                        <td><?php echo h($post['Post']['title']); ?>&nbsp;</td>
                        <td><?php echo h($post['Post']['publish']); ?>&nbsp;</td>
                        <td><?php echo h($post['Post']['show_link']); ?>&nbsp;</td>
                        <td><?php echo h($post['Post']['show_frontpage']); ?>&nbsp;</td>
                        <td><?php echo h($post['Post']['created']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $post['Post']['id'])); ?>
                            <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $post['Post']['id']), null, __('Are you sure you want to delete # %s?', $post['Post']['id'])); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
            </table>
            <p>
                <?php
                echo $this->Paginator->counter(array(
                    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
                ));
                ?>    </p>

            <div class="paging">
                <?php
                echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
                echo $this->Paginator->numbers(array('separator' => ''));
                echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
                ?>
            </div>
        </div>
    </div>
</div>