<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" xmlns="http://www.w3.org/1999/html"> <!--<![endif]-->

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title><?php echo 'studiumdigitale eLectureportal - ' . $title_for_layout; ?></title>

        <meta name="description" content="Die Goethe-Universität ist eine forschungsstarke Hochschule in der europäischen Finanzmetropole Frankfurt. Lebendig, urban und weltoffen besitzt sie als Stiftungsuniversität ein einzigartiges Maß an Eigenständigkeit.">
        <meta name="viewport" content="width=device-width">

        <?php
        echo $this->Html->css('bootstrap.min.css');
        echo $this->Html->css('bootstrap-responsive.min.css');
        echo $this->Html->css('main');
        ?>

        <script>
        UFM = {};
        UFM.ep = {};
        UFM.ep.controller = '<?php echo $this->request->params['controller']; ?>';
        UFM.ep.action = '<?php echo $this->request->params['action']; ?>';
        UFM.ep.currentId = <?php echo ((isset($id)) ? $id : 'null'); ?>;
        UFM.ep.here = '<?php echo rtrim($this->request->here, '/') . '/'; ?>';
        UFM.ep.baseUrl = '<?php echo $this->request->base; ?>';
        </script>

        <!--[if lt IE 9]>
        <?php echo $this->Html->script('vendor/html5-3.6-respond-1.1.0.min'); ?>
        <![endif]-->

        <script src="http://code.jquery.com/jquery-1.9.0.js"></script>

        <?php echo $this->Html->css('jquery-ui-1.9.2.custom.min'); ?>
        <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>

        <?php echo $this->Html->script('vendor/bootstrap.min'); ?>
    </head>

    <body>
        <!--[if lt IE 7]>
        <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser
            today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to
            better experience this site.</p>
        <![endif]-->

        <div class="container">

            <div class="row row-header">

                <span class="logo">
                    <?php echo $this->Html->image('head_logo.png'); ?>
                </span>

                <form id="formSearch" class="form-search span4 pull-right" method="POST" action="<?php echo $this->Html->url(array('controller' => 'lectures', 'action' => 'search'));?>">
                    <div class="input-append">
                        <input type="text" name="term" placeholder="Suchbegriff" class="span3 search-query" required="true" />
                        <button type="submit" class="btn"><?php echo __('Suchen'); ?></button>
                    </div>
                </form>

            </div>

            <div class="row row-content">
                <?php echo $this->element( 'navbar', (isset($categories) ? $categories : null) ); ?>

                <?php if($this->Session->check('Message.flash')): ?>
                <div class="alert alert-info span11" style="margin-bottom: 20px">
                    <?php echo $this->Session->flash(); ?>
                    <?php echo $this->Session->flash('auth'); ?>
                </div>
                <?php endif; ?>

                <?php echo $this->fetch('content'); ?>
            </div>

            <?php if ($debugMode == 2): ?>
            <div class="hero-unit">
                <?php echo $this->element('sql_dump'); ?>
            </div>
            <?php endif; ?>

        </div>

        <?php echo $this->Html->script('plugins.js'); ?>
        <?php echo $this->Html->script('main.js'); ?>
    </body>
</html>