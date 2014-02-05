<div class="span9">
<?php echo Form::open(array('class' => "form-inline form-horizontal")); ?>

	

                <div class="control-group">
			<?php echo Form::label('Username', 'username'); ?>
			<div class="controls">
				<?php echo Form::input('username', Input::post('username')); ?>
			</div>
		</div>
                <div class="control-group">
			<?php echo Form::label('E-Mail', 'email'); ?>
			<div class="controls">
				<?php echo Form::input('email', Input::post('email')); ?>
			</div>
		</div>

                <div class="control-group">
			<?php echo Form::label('Password', 'password'); ?>
			<div class="controls">
				<?php echo Form::password('password'); ?>
			</div>
		</div>

	

	<div class="form-actions">
		<?php echo Form::submit(array('value'=>'Register', 'name'=>'submit')); ?>
	</div>
       
<?php echo Form::close(); ?>
 </div>