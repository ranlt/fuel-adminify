<?php echo Form::open(array('class' => "form-inline form-horizontal")); ?>

		
		<div class="control-group">
			<?php echo Form::label('Menu Title', 'title', array('class' => 'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('title', Input::post('title', isset($menu) ? $menu->title : ''), array('class' => 'span5')); ?>

			</div>
		</div>
            
                <div class="control-group">
			<?php echo Form::label('Menu Link', 'link', array('class' => 'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('link', Input::post('link', isset($menu) ? $menu->link : ''), array('class' => 'span5')); ?>

			</div>
		</div>
            
                <div class="control-group">
                    <?php echo Form::label('Category', 'catid', array('class' => 'control-label')); ?>
                    
                    <div class="controls">
                        <!--
                            if you're using an admin interface with a url like
                            admin/menu/create/4 you have to change the uri::segment() element.
                            currently, the segment search the parent category at the third position
                            menu/create/4
                        -->
                        <?php echo Form::select('catid', \Input::post('catid', isset($menu) ? $menu->catid : \Uri::segment(3,0)), $categories, array('id' => 'admin_parent_category')); ?>
                    </div>
		</div>
                
		<div class="control-group">
			<?php echo Form::label('Parent Link', 'parent', array('class' => 'control-label')); ?>
                    
                        <div class="controls">
                            <?php echo Form::select('parent', \Input::post('parent', isset($menu) ? $menu->parent : 0), $parent_links, array('id' => 'admin_parent_links')); ?>
                        </div>
		</div>
            
                <div class="control-group">
			<?php echo Form::label('Icon', 'menuicon', array('class' => 'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('menuicon', Input::post('menuicon', isset($menu) ? $menu->menuicon : 'none'), array('class' => 'span5')); ?>

			</div>
		</div>
            
                <div class="control-group">
			<?php echo Form::label('Position', 'position', array('class' => 'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('position', Input::post('position', isset($menu) ? $menu->position : 0), array('class' => 'span5')); ?>

			</div>
		</div>
            
		<div class="control-group">
			<?php echo Form::label('Active', 'active', array('class' => 'control-label')); ?>

			<div class="controls">
                            <?php (\Input::post('active') === '1' || (isset($menu) && $menu->active === '1')) ? $attributes['checked'] = 'checked' : $attributes = array(); ?>
                            <?php echo Form::checkbox('active', '1', $attributes); ?>
			</div>
		</div>
            
                <div class="control-group">
			<?php echo Form::label('Divider', 'divider', array('class' => 'control-label')); ?>

			<div class="controls">
                            <?php (\Input::post('divider') === '1' || (isset($menu) && $menu->divider === '1')) ? $attributes['checked'] = 'checked' : $attributes = array(); ?>
                            <?php echo Form::checkbox('divider', '1', $attributes); ?>
			</div>
		</div>
            
		<div class="form-actions">
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?> <?php echo \Html::anchor('admin/menu', 'Cancel', array('class' => 'btn btn-danger')); ?>

		</div>
<?php echo Form::close(); ?>


<script type="text/javascript">
        {
            <?php
                echo"var baseURL='".Uri::base(false)."';";
            ?>
        }
</script>
<script type="text/javascript">
    $("#admin_parent_category").change(function() 
    {
        $("#admin_parent_links").html("");
        $.ajax({
            type: "POST",
            url: baseURL+"index.php/menu/ajax/EntriesByCategory",
            cache: true,
            dataType: "json",
            data: $("#admin_parent_category").serialize(),
            success:function(data) 
            {
                $.each( data, function(key, value)
                {
                   $("#admin_parent_links").append('<option value="'+value.id+'">'+value.displayname+'</option> \n');
                });        
            }
        });
    });    
</script>