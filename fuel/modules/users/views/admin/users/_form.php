<?php echo Form::open(array('class' => "form-inline form-horizontal")); ?>

	<div class="span12">

                <div class="control-group">
			<?php echo \Form::label('Username', 'username', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo Form::input('username', \Input::post('username', isset($user) ? $user->username : '')); ?>
			</div>
		</div>
                <div class="control-group">
			<?php echo \Form::label('E-Mail', 'email', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo Form::input('email', \Input::post('email', isset($user) ? $user->email : '')); ?>
			</div>
		</div>

                <div class="control-group">
			<?php echo \Form::label('Password', 'password', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo Form::input('password', \Fuel\Core\Input::post('password')); ?>
                <br /><span class="help-block">Leave it blank, if you don't want to change it.</span>

			</div>
		</div>
                <div class="control-group">
                    
			<?php echo \Form::label('Member of', 'role', array('class' => 'control-label')); ?>
			<div class="controls">
                            
                <?php foreach($roles as $value => $key): ?>

                    <?php
                        $checked = (isset($user->roles[$value])) ? true : false; 
                    ?>
                    <?php echo Form::checkbox('role[]', $value, $checked); ?> <?php echo $key; ?><br />

                <?php endforeach; ?>
                            
                            
                            

			</div>
		</div>
            
                <div class="control-group">
                    <?php echo \Form::label('Activated', 'is_confirmed', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?php 
                            if((isset($user->is_confirmed) && $user->is_confirmed === '1') || Input::post('is_confirmed') === '1')
                            {
                                $active = true;
                            }
                            else
                            {
                                $active = false;
                            }
                        ?>
                        <?php ($active) ? $attributes = array('checked' => 'checked') : $attributes = array(); ?>
                        <?php echo Form::checkbox('is_confirmed', '1', $attributes); ?>
                    </div>

                </div>

	

	<div class="form-actions">
                <?php if(!isset($user)): ?>
                    <?php echo Form::submit(array('value'=>'Create User', 'name'=>'submit', 'class'=>'btn btn-success')); ?>
                <?php else: ?>
                    <?php echo Form::submit(array('value'=>'Update User', 'name'=>'submit', 'class'=>'btn btn-success')); ?>
                <?php endif; ?>
                <?php echo \Html::anchor('users/admin/users', 'Back', array('class' => 'btn btn-info')); ?>
	</div>
        </div>
<?php echo Form::close(); ?>