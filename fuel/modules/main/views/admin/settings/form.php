<?php echo Form::open(array('class' => "form-inline form-horizontal")); ?>

        
        <div class="control-group">
            <?php echo Form::label('Website Title', 'title', array('class' => 'control-label')); ?>

            <div class="controls">
                <?php echo Form::input('website_title', Input::post('website_title', \Config::get('website.website_title', '')), array('class' => 'span5')); ?>

            </div>
        </div>
            
        <div class="form-actions">
            <?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>

        </div>
<?php echo Form::close(); ?>