<?php echo \Form::open(array('action' => 'installer/admin', 'class'=>'form-horizontal', 'role'=>'form')); ?>

	<?php echo \Form::csrf(); ?>

  	<h2>Create Admin User<small></small></h2>
	<div class="form-group">
	    <label for="form_username" class="col-sm-3 control-label">Username:</label>
	    
	    <div class="col-sm-9">
      		<?php echo Form::input('username', \Input::post('username'), array('class' => 'form-control', 'placeholder' => 'pseudohero', 'autocomplete' => 'off')); ?>
    	</div>

  	</div>

  	<div class="form-group">
	    <label for="form_password" class="col-sm-3 control-label">Password:</label>
	    
	    <div class="col-sm-9">
      		<?php echo Form::password('password', '', array('class' => 'form-control', 'placeholder' => '*********', 'autocomplete' => 'off')); ?>
    	</div>

  	</div>

  	<div class="form-group">
	    <label for="form_email" class="col-sm-3 control-label">E-Mail:</label>
	    
	    <div class="col-sm-9">
      		<?php echo Form::input('email', \Input::post('email'), array('class' => 'form-control', 'placeholder' => 'hello@pseudoagentur.de', 'autocomplete' => 'off')); ?>
    	</div>

  	</div>


<?php if(!$next_step) :?>
<div class="pull-left text-left">
	<?php echo \Form::button('submit','Create Admin', array('type' => 'submit','class' => 'btn btn-warning btn-lg')); ?>
</div>
<?php endif; ?>

<?php echo \Form::close(); ?>

<?php if($next_step) :?>
<div class="pull-right text-right">
	
		<?php echo \Html::anchor('installer/finish', 'Go to next step', array('class' => 'btn btn-success btn-lg')); ?>
	
</div>
<?php endif; ?>