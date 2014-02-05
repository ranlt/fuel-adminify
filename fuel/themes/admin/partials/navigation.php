<div class="navbar navbar-fixed-top navbar-inverse" id="header-navigation" role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <button type="button" class="btn btn-adminify left-navigation-button" id="showLeft">
      <span class="glyphicon glyphicon-align-right"></span>
    </button>
    <a class="navbar-brand" href="#">Adminify</a>
  </div>
  <div class="collapse navbar-collapse">
    <ul class="nav navbar-nav navbar-right">
      <?php echo \Menu::build('user'); ?>
    </ul>
  </div>
</div>