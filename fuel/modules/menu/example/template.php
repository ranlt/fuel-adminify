<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My admin</title>

    <!-- Core CSS - Include with every page -->
    <?= \Theme::instance()->asset->render('css_core'); ?>

    <!-- Page-Level Plugin CSS - Dashboard -->
    <?= \Theme::instance()->asset->render('css_plugin'); ?>

    <!-- Core JS - jQuery -->
    <?= \Theme::instance()->asset->render('js_core'); ?>
</head>

<body>
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <h1><?= $pageTitle; ?></h1>
                <?php if (isset($partials['content'])): ?>
                    <?= $partials['content']; ?>
                <?php endif; ?>
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Page-Level Plugin Scripts - Dashboard -->
    <?= \Theme::instance()->asset->render('js_plugin'); ?>

    <?= \Theme::instance()->asset->render('js_footer'); ?>
</body>

</html>
