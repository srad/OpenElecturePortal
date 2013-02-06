<?php echo $this->Html->script('jquery.dataTables.min'); ?>

<div class="row-fluid">
    <div class="content-padding">
        <table id="overview" class="overview table table-striped table-condensed table-bordered">
            <thead>
            <tr>
                <th><?php echo __('Abo'); ?></th>
                <th><?php echo __('Titel'); ?></th>
                <th><?php echo __('Semester'); ?></th>
            </tr>
            </thead>

            <tbody>
            <?php foreach($lectures as $lecture): ?>
                <?php $link = $this->Html->url(
                    array(
                        'controller' => 'lectures',
                        'action' => 'view',
                        $lecture['Lecture']['id'],
                        $lecture['Lecture']['category_id'],
                        $lecture['Term']['id'],
                        'ext' => 'rss',
                    )
                ); ?>
                <tr>
                    <td><a href="<?php echo $link; ?>"><i class="icon-bookmark"></i> <?php echo __('RSS'); ?></a></td>

                    <td>
                    <?php echo $this->Html->link(
                        $lecture['Lecture']['name'],
                        array(
                            'controller' => 'lectures',
                            'action' => 'view',
                            $lecture['Lecture']['id'],
                            $lecture['Lecture']['category_id'],
                            $lecture['Term']['id']
                        )
                    ); ?>
                    </td>

                    <td><?php echo $lecture['Term']['name']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
#overview_length { float:right; }
</style>

<script>
$(function () {
    $('#overview').dataTable({
        'oLanguage': {
            sSearch: '<?php echo __('Suche'); ?>',
        },
        bPaginate: false
    });
});
</script>