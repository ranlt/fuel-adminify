<?php echo Form::open(array('class' => "form-inline form-horizontal")); ?>


        <div class="control-group">
			<?php echo \Form::label('Name', 'name', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo Form::input('name', \Input::post('name', isset($permission) ? $permission->name : '')); ?>
			</div>
		</div>

        <div class="control-group">
            <?php echo \Form::label('Resource', 'resource', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo Form::input('resource', \Input::post('resource', isset($permission) ? $permission->resource : '')); ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo \Form::label('Action', 'action', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo Form::input('action', \Input::post('action', isset($permission) ? $permission->action : '')); ?>
            </div>
        </div>

        <div class="control-group">
			<?php echo \Form::label('Description', 'description', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo Form::input('description', \Input::post('description', isset($permission) ? $permission->description : '')); ?>
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