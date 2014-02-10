<?php echo \Form::open(array('action' => 'installer/settings', 'class'=>'form-horizontal', 'role'=>'form')); ?>

	<?php echo \Form::csrf(); ?>

  	<h2>Database Connection<small> - mySQL only!</small></h2>
	<div class="form-group">
	    <label for="form_db_host" class="col-sm-3 control-label">Host:</label>
	    
	    <div class="col-sm-9">
      		<?php echo Form::input('db_host', \Input::post('db_host'), array('class' => 'form-control', 'placeholder' => 'localhost')); ?>
    	</div>

  	</div>

  	<div class="form-group">
	    <label for="form_db_username" class="col-sm-3 control-label">Username:</label>
	    
	    <div class="col-sm-9">
      		<?php echo Form::input('db_username', \Input::post('db_username'), array('class' => 'form-control', 'placeholder' => 'usr_web123')); ?>
    	</div>

  	</div>

  	<div class="form-group">
	    <label for="form_db_password" class="col-sm-3 control-label">Password:</label>
	    
	    <div class="col-sm-9">
      		<?php echo Form::input('db_password', \Input::post('db_password'), array('class' => 'form-control', 'placeholder' => '*****')); ?>
    	</div>

  	</div>

  	<div class="form-group">
	    <label for="form_db_name" class="col-sm-3 control-label">Name:</label>
	    
	    <div class="col-sm-9">
      		<?php echo Form::input('db_name', \Input::post('db_name'), array('class' => 'form-control', 'placeholder' => 'usr_web123_1')); ?>
    	</div>

  	</div>


<?php if(!$next_step) :?>
<div class="pull-left text-left">
	<?php echo \Form::button('submit','Save Settings', array('type' => 'submit','class' => 'btn btn-warning btn-lg')); ?>
</div>
<?php endif; ?>

<?php echo \Form::close(); ?>

<?php if($next_step) :?>
<div class="pull-right text-right">
	
		<?php echo \Html::anchor('installer/database', 'Go to next step', array('class' => 'btn btn-success btn-lg')); ?>
	
</div>
<?php endif; ?>