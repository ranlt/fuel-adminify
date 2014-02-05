<!DOCTYPE html>
<html lang="en">
    <head>


        <title><?php echo \Config::get('website.website_title'); ?> <?php echo (($title)) ? ' - '.$title : ''; ?></title>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Pseudoagentur">

        <!-- Bootstrap core CSS -->
        <?php echo \Theme::instance()->asset->css('bootstrap.min.css'); ?>

        <!-- Custom styles for this template -->
        <?php echo \Theme::instance()->asset->css('bootstrap-mobile-navigation.css'); ?>
        <?php echo \Theme::instance()->asset->css('../font-awesome/css/font-awesome.min.css'); ?>
        <?php echo \Theme::instance()->asset->css('flatui-buttons.css'); ?>
        <?php echo \Theme::instance()->asset->css('theme-adminify.css'); ?>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->


        <script type="text/javascript">
        {
            <?php
                echo"var baseURL='".Uri::base(false)."';";
            ?>
        }
        </script>   
    </head>
	<body>

        <!-- START partials/header.html -->
        <?php echo $partials['navigation']; ?>
        <!-- END partials/header.html -->



        <!-- START partials/sidebar.html -->
        <?php echo $partials['sidebar']; ?>
        <!-- END partials/sidebar.html -->


        <div class="container-adminify">
            <!-- START partials/page-header.html -->
            <?php echo $partials['page_header']; ?>
            <!-- END partials/page-header.html -->


            <?php echo $partials['alert_messages']; ?>
            <?php echo $content; ?>
        </div>

        <!-- START partials/footer.html -->
        <?php echo $partials['footer']; ?>
        <!-- END partials/footer.html -->
	</body>

</html>