<?php echo Form::open(array('class' => "form-inline form-horizontal")); ?>

	<div class="span6">

                <div class="control-group">
			<?php echo Form::label('New Password', 'new_password'); ?>
			<div class="controls">
				<?php echo Form::password('new_password'); ?>
			</div>
		</div>

                

	

	<div class="form-actions">
		<?php echo Form::submit(array('value'=>'Set new password', 'name'=>'submit', 'class' => 'btn btn-success')); ?> 
                  
	</div>
        </div>
<?php echo Form::close(); ?>