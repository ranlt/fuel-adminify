<?php echo Form::open(array('class' => "form-inline form-horizontal")); ?>


        <div class="control-group">
			<?php echo \Form::label('Name', 'name', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo Form::input('name', \Input::post('name', isset($role) ? $role->name : '')); ?>
			</div>
		</div>
        <div class="control-group">
			<?php echo \Form::label('Description', 'description', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo Form::input('description', \Input::post('description', isset($role) ? $role->description : '')); ?>
			</div>
		</div>

        <div class="control-group">
            <?php echo \Form::label('Permissions', 'permissions', array('class' => 'control-label')); ?>

            <div class="controls">       

                <?php foreach($permissions as $resource => $actions): ?>

                    <div class="span4">
                        
                        <h5><?php echo ucfirst($resource); ?></h5>
                        
                        <?php foreach($actions as $key => $action): ?>

                            <p>
                            <?php $checked = (isset($role->permissions[$action['id']])) ? true : false; ?>
                            <?php echo \Form::checkbox('permission[]', $action['id'], $checked); ?> <?php echo ucfirst($action['action']); ?>
                            <span class="help-block"><?php echo $action['description']; ?></span>
                            </p>

                        <?php endforeach; ?>
                    
                    </div>

                <?php endforeach; ?>
                    
            </div>
        </div>  
            
                
	

    	<div class="form-actions">
                    <?php if(!isset($role)): ?>
                        <?php echo Form::submit(array('value'=>'Create Role', 'name'=>'submit', 'class'=>'btn btn-success')); ?>
                    <?php else: ?>
                        <?php echo Form::submit(array('value'=>'Update Role', 'name'=>'submit', 'class'=>'btn btn-success')); ?>
                    <?php endif; ?>
                    <?php echo \Html::anchor('admin/users/roles', 'Back', array('class' => 'btn btn-info')); ?>
    	</div>
<?php echo Form::close(); ?>