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
                    <li class="divider-vertical"></li>

                    <li data-controller="lectures" data-action="overview" data-id="*"><?php echo $this->Html->link(__('Übersicht aller Veranstaltungen'), '/lectures/overview'); ?></li>
                    <li class="divider-vertical"></li>

                    <?php if (isset($categories)): ?>
                    <?php foreach ($categories as $id => $category): ?>
                        <li data-controller="categories||lectures" data-action="*" data-id="<?php echo $id; ?>"><?php echo $this->Html->link($category, '/categories/view/' . $id); ?></li>
                    <?php endforeach; ?>
                    <li class="divider-vertical"></li>
                    <?php endif; ?>

                    <li data-controller="videos" data-action="latest" data-id="*"><?php echo $this->Html->link(__('Neuste Videos'), '/videos/latest'); ?></li>
                </ul>

                <ul class="nav pull-right">

                    <?php if ($loggedIn) : ?>
                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user icon-white"></i> <?php echo __('Administration'); ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><?php echo $this->Html->link(__('Menüpunkte'), '/admin/lectures'); ?></li>
                            <li><?php echo $this->Html->link(__('Semester'), '/admin/terms'); ?></li>
                            <li><?php echo $this->Html->link(__('Kategorien'), '/admin/categories'); ?></li>
                            <?php if ($group == 'admin'): ?>
                                <li class="divider"></li>
                                <li><?php echo $this->Html->link(__('Benutzer-Übersicht'), '/admin/users'); ?></li>
                                <li><?php echo $this->Html->link(__('Benutzer anlegen'), '/admin/users/add'); ?></li>
                                <li class="divider"></li>
                                <li><?php echo $this->Html->link(__('Post anlegen'), '/admin/posts/add'); ?></li>
                            <?php endif; ?>
                            <li class="divider"></li>
                            <li><?php echo $this->Html->link(__('Abmelden'), '/users/logout'); ?></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>