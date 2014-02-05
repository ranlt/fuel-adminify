<div class="row">

    <?php echo Form::open(array('class' => "form-horizontal")); ?>
		<?php echo \Form::csrf(); ?>
        <div class="control-group">
            <?php echo Form::label('E-Mail / Username', 'username_or_email', array('class'=>'control-label', 'for'=>'username_or_email')); ?>
            <div class="controls">
                    <?php echo Form::input('username_or_email', Input::post('username_or_email')); ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo Form::label('Password', 'password', array('class'=>'control-label', 'for'=>'password')); ?>
            <div class="controls">
                    <?php echo Form::password('password'); ?>
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                <label class="checkbox">
                    <?php echo Form::checkbox('remember_me', true); ?> Remember me
                </label>
            </div>
        </div>

        <div class="form-actions">
            <?php echo Form::submit(array('value'=>'Login', 'name'=>'submit', 'class' => 'btn btn-success')); ?> 
            <?php echo \Html::anchor('users/forgot', 'Forgot Password', array('class' =>'btn btn-info')); ?>
        </div>
    <?php echo Form::close(); ?>
</div>