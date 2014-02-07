<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8">
        <title><?php echo \Config::get('website.website_title'); ?> <?php echo (($title)) ? ' - '.$title : ''; ?></title>
        
        <meta name="description" content="">
        <meta name="author" content="">

        <?php echo \Theme::instance()->asset->css('bootstrap.min.css'); ?>

        

        <script type="text/javascript">
        {
            <?php
                echo"var baseURL='".Uri::base(false)."';";
            ?>
        }
        </script>   
    </head>
	<body>
		
        <div class="container">
            
            <div class="row">
                
                <div class="col-xs-12">
                    
                    <div class="page-header">
                        <h1><?php echo \Config::get('website.website_title', 'Fuel Adminify'); ?> - Installer</h1>
                    </div>
                </div>

            </div>

            <div class="row">
                
                <div class="col-xs-12">
                    <?php echo $partials['alert_messages']; ?>
                </div>

            </div>

            <div class="row">
                
                <div class="col-sm-3">
                    <?php echo $partials['navigation']; ?>
                </div>

                <div class="col-sm-9">
                    <?php echo $content; ?>
                </div>

            </div>

        </div>

		  

		<?php echo \Theme::instance()->asset->js('jquery.min.js'); ?>
        <?php echo \Theme::instance()->asset->js('bootstrap.min.js'); ?>
        
	</body>
</html>