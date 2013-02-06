<div class="row-fluid">
    <div class="content-padding">
        <h1><?php echo __('Suchergebnisse für "%s"', isset($search) ? $search : __('nichts!')); ?></h1>

        <?php if (!isset($lectures) || empty($lectures)): ?>
        <div class="hero-unit">
            <?php echo __('Keine Veranstaltungen gefunden.'); ?>
        </div>
        <?php else: ?>
            <table id="overview" class="overview table table-striped table-condensed table-bordered">
                <thead>
                <tr>
                    <th><?php echo __('Abo'); ?></th>
                    <th><?php echo __('Titel'); ?></th>
                    <th><?php echo __('Semester'); ?></th>
                    <th><?php echo __('Sprecher'); ?></th>
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
                    <td><?php echo $lecture['Video']['speaker']; ?></td>
                </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>