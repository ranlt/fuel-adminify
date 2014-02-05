<div class="row">
	<?php echo Form::open(array('class' => "form-inline form-horizontal")); ?>

        <div class="control-group">
			<?php echo Form::label('E-Mail', 'email'); ?>
			<div class="controls">
				<?php echo Form::input('email', Input::post('email')); ?>
			</div>
		</div>

		<div class="form-actions">
			<?php echo Form::submit(array('value'=>'Send', 'name'=>'submit', 'class' => 'btn btn-success')); ?> 
		</div>
		
	<?php echo Form::close(); ?>
</div>