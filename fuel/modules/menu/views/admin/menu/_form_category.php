<?php echo \Form::open(array('class' => "form-horizontal")); ?>

		
    <div class="control-group">
            <?php echo \Form::label('Category Name', 'catname', array('class' => 'control-label')); ?>

            <div class="controls">
                    <?php echo \Form::input('catname', \Input::post('catname', isset($category) ? $category->catname : ''), array('class' => 'span5')); ?>

            </div>
    </div>

    <div class="control-group">
            <?php echo \Form::label('Category Alias', 'alias', array('class' => 'control-label')); ?>

            <div class="controls">
                    <?php echo \Form::input('alias', \Input::post('alias', isset($category) ? $category->alias : ''), array('class' => 'span5')); ?>

            </div>
    </div>

    <div class="form-actions">
            <?php echo \Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?> <?php echo \Html::anchor('menu', 'Cancel', array('class' => 'btn btn-danger')); ?>

    </div>

<?php echo \Form::close(); ?>