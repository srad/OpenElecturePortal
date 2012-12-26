<div class="hero-unit links index span11">
    <h2><?php echo __('Links'); ?></h2>
    <table class="table table-bordered table-striped table-condensed">
        <tr>
            <th><?php echo $this->Paginator->sort('title'); ?></th>
            <th><?php echo $this->Paginator->sort('url'); ?></th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        <?php
        foreach ($links as $link): ?>
            <tr>
                <td><?php echo h($link['Link']['title']); ?>&nbsp;</td>
                <td><?php echo h($link['Link']['url']); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $link['Link']['id'])); ?>
                    <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $link['Link']['id']), null, __('Are you sure you want to delete # %s?', $link['Link']['id'])); ?>
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