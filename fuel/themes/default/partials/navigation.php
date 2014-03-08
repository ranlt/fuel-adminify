<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            
            <div class="nav-collapse">
                <ul class="nav">


                </ul>
                <ul class="nav pull-right">
                    <?php if($current_user == "Guest"): ?>
                    <?php else: ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>