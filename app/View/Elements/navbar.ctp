<div class="navbar navbar-inverse">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li data-controller="posts" data-action="index" data-id="*"><?php echo $this->Html->link(__('Startseite'), '/startseite'); ?></li>
                    <li data-controller="videos" data-action="latest" data-id="*"><?php echo $this->Html->link(__('Neuste Videos'), array('controller' => 'videos', 'action' => 'latest')); ?></li>
                    <?php if (isset($categories)): ?>
                    <?php foreach ($categories as $id => $category): ?>
                        <li data-controller="categories||listings" data-action="*" data-id="<?php echo $id; ?>"><?php echo $this->Html->link($category, '/categories/view/' . $id); ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>