<?php echo Form::open(array('class' => "form-inline form-horizontal")); ?>


        <div class="tabbable tabs-left">

            <ul class="nav nav-tabs">
              <li class="active"><a href="#users_general" data-toggle="tab">General</a></li>
              <li><a href="#users_recover" data-toggle="tab">Recover</a></li>
              <li><a href="#users_confirm" data-toggle="tab">Confirm</a></li>
              <li><a href="#users_lock" data-toggle="tab">Lock</a></li>
            </ul>

            <div class="tab-content">

                <div class="tab-pane active" id="users_general">
                    
                    <div class="control-group">
                        <?php echo Form::label('Remeber Me Lifetime', 'warden_lifetime', array('class' => 'control-label')); ?>

                        <div class="controls">
                                <?php echo Form::input('warden_lifetime', Input::post('warden_lifetime', \Config::get('warden.lifetime', 1209600)), array('class' => 'span5')); ?>

                        </div>
                    </div>
                    
                    <div class="control-group">
                        <?php echo Form::label('Default Role', 'warden_default_role', array('class' => 'control-label')); ?>

                        <div class="controls">
                                <?php echo Form::input('warden_default_role', Input::post('warden_default_role', \Config::get('warden.default_role', 'User')), array('class' => 'span5')); ?>

                        </div>
                    </div>
                    
                    <div class="control-group">
                        <?php echo Form::label('Profilable', 'warden_profilable', array('class' => 'control-label')); ?>

                        <div class="controls">
                            <?php (\Input::post('warden_profilable') == '1' || (\Config::get('warden.profilable') == '1')) ? $attributes['checked'] = 'checked' : $attributes = array(); ?>
                            <?php echo Form::checkbox('warden_profilable', '1', $attributes); ?>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <?php echo Form::label('Trackable', 'warden_trackable', array('class' => 'control-label')); ?>

                        <div class="controls">
                            <?php (\Input::post('warden_trackable') == '1' || (\Config::get('warden.trackable') == '1')) ? $attributes['checked'] = 'checked' : $attributes = array(); ?>
                            <?php echo Form::checkbox('warden_trackable', '1', $attributes); ?>
                        </div>
                        
                    </div>
                    
                </div>

                <div class="tab-pane" id="users_recover">
                    
                    <div class="control-group">
                        <?php echo Form::label('In Use', 'warden_recoverable_in_use', array('class' => 'control-label')); ?>

                        <div class="controls">
                            <?php (\Input::post('warden_recoverable_in_use') == '1' || (\Config::get('warden.recoverable.in_use') == '1')) ? $attributes['checked'] = 'checked' : $attributes = array(); ?>
                            <?php echo Form::checkbox('warden_recoverable_in_use', '1', $attributes); ?>
                        </div>
                        
                    </div>
                    
                    <div class="control-group">
                        <?php echo Form::label('Reset within', 'warden_recoverable_reset_password_within', array('class' => 'control-label')); ?>

                        <div class="controls">
                                <?php echo Form::input('warden_recoverable_reset_password_within', Input::post('warden_recoverable_reset_password_within', \Config::get('warden.recoverable.reset_password_within', '+1 week')), array('class' => 'span5')); ?>

                        </div>
                    </div>
                    
                    <div class="control-group">
                        <?php echo Form::label('Recover Url', 'warden_recoverable_url', array('class' => 'control-label')); ?>

                        <div class="controls">
                                <?php echo Form::input('warden_recoverable_url', Input::post('warden_recoverable_url', \Config::get('warden.recoverable.url', 'users/reset')), array('class' => 'span5')); ?>

                        </div>
                    </div>
                    
                    
                </div>

                <div class="tab-pane" id="users_confirm">
                    <div class="control-group">
                        <?php echo Form::label('In Use', 'warden_confirmable_in_use', array('class' => 'control-label')); ?>

                        <div class="controls">
                            <?php (\Input::post('warden_confirmable_in_use') == '1' || (\Config::get('warden.confirmable.in_use') == '1')) ? $attributes['checked'] = 'checked' : $attributes = array(); ?>
                            <?php echo Form::checkbox('warden_confirmable_in_use', '1', $attributes); ?>
                        </div>
                        
                    </div>
                    
                    <div class="control-group">
                        <?php echo Form::label('Confirm within', 'warden_confirmable_confirm_within', array('class' => 'control-label')); ?>

                        <div class="controls">
                                <?php echo Form::input('warden_confirmable_confirm_within', Input::post('warden_confirmable_confirm_within', \Config::get('warden.confirmable.confirm_within', '+1 week')), array('class' => 'span5')); ?>

                        </div>
                    </div>
                    
                    <div class="control-group">
                        <?php echo Form::label('Confirm Url', 'warden_confirmable_url', array('class' => 'control-label')); ?>

                        <div class="controls">
                                <?php echo Form::input('warden_confirmable_url', Input::post('warden_confirmable_url', \Config::get('warden.confirmable.url', 'users/reset')), array('class' => 'span5')); ?>

                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="users_lock">
                    <div class="control-group">
                        <?php echo Form::label('In Use', 'warden_lockable_in_use', array('class' => 'control-label')); ?>

                        <div class="controls">
                            <?php (\Input::post('warden_lockable_in_use') == '1' || (\Config::get('warden.lockable.in_use') == '1')) ? $attributes['checked'] = 'checked' : $attributes = array(); ?>
                            <?php echo Form::checkbox('warden_lockable_in_use', '1', $attributes); ?>
                        </div>
                        
                    </div>
                    
                    <div class="control-group">
                        <?php echo Form::label('Maximum attempts', 'warden_lockable_maximum_attempts', array('class' => 'control-label')); ?>

                        <div class="controls">
                            <?php echo Form::input('warden_lockable_maximum_attempts', Input::post('warden_lockable_maximum_attempts', \Config::get('warden.lockable.maximum_attempts', 10)), array('class' => 'span5')); ?>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <?php echo Form::label('Lock strategy', 'warden_lockable_lock_strategy', array('class' => 'control-label')); ?>

                        <div class="controls">
                            <?php echo Form::input('warden_lockable_lock_strategy', Input::post('warden_lockable_lock_strategy', \Config::get('warden.lockable.lock_strategy', 'sign_in_count')), array('class' => 'span5')); ?>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <?php echo Form::label('Unlock strategy', 'warden_lockable_unlock_strategy', array('class' => 'control-label')); ?>

                        <div class="controls">
                            <?php echo Form::input('warden_lockable_unlock_strategy', Input::post('warden_lockable_unlock_strategy', \Config::get('warden.lockable.unlock_strategy', 'both')), array('class' => 'span5')); ?>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <?php echo Form::label('Unlock in', 'warden_lockable_unlock_in', array('class' => 'control-label')); ?>

                        <div class="controls">
                            <?php echo Form::input('warden_lockable_unlock_in', Input::post('warden_lockable_unlock_in', \Config::get('warden.lockable.unlock_in', '+1 week')), array('class' => 'span5')); ?>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <?php echo Form::label('Unlock Url', 'warden_lockable_url', array('class' => 'control-label')); ?>

                        <div class="controls">
                            <?php echo Form::input('warden_lockable_url', Input::post('warden_lockable_url', \Config::get('warden.lockable.url', 'users/unlock')), array('class' => 'span5')); ?>
                        </div>
                    </div>
                    
                    
                    
                    
                </div>

            </div>
        </div>
        
        <div class="form-actions">
            <?php echo Form::submit(array('value'=>'Save', 'name'=>'submit', 'class'=>'btn btn-success')); ?>
            <?php echo \Html::anchor('admin/user/settings', 'Cancel', array('class' => 'btn btn-danger')); ?>
	</div>
        
<?php echo Form::close(); ?>