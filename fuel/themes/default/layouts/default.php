<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8">
        <title><?php echo \Config::get('website.website_title'); ?> <?php echo (($title)) ? ' - '.$title : ''; ?></title>
        
        <meta name="description" content="">
        <meta name="author" content="">

        <?php echo \Theme::instance()->asset->css('bootstrap.css'); ?>

        <style type="text/css">
            body {
                padding-top: 40px;
                padding-bottom: 40px;
            }
            .sidebar-nav {
                padding: 9px 0;
            }

            .navbar .divider-vertical {
                height: 40px;
                margin: 0 2px;
                border-left: 1px solid #e2e2e2;
                border-right: 1px solid white;
        </style>

        <script type="text/javascript">
        {
            <?php
                echo"var baseURL='".Uri::base(false)."';";
            ?>
        }
        </script>   
    </head>
	<body>
		

		        <?php echo $partials['navigation']; ?>
        
        <?php if(isset($partials['subnavigation'])): ?>
            <?php echo $partials['subnavigation']; ?>
        <?php else: ?>
            <br/>
        <?php endif; ?>
        
        <div class="container">
                  		
      		<?php echo $partials['alert_messages']; ?>

            <div class="row">
                
                <div class="span12">
                    <?php echo \Breadcrumb::create_links(); ?>
                    <?php echo $content; ?>
                </div>
                
                
            </div><!--/row-->
    
            <?php //echo \Template::theme_partial('footer'); ?>
        
        </div>

		<?php echo \Theme::instance()->asset->js('jquery.js'); ?>
        <?php echo \Theme::instance()->asset->js('bootstrap.js'); ?>
        <?php echo \Theme::instance()->asset->js('bootstrap-tooltip.js'); ?>
	</body>
</html>