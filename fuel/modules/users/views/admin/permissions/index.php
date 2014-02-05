<?php if ($permissions): ?>
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th class="span1">ID</th>
                <th class="span2">Resource</th>
                <th class="span2">Action</th>
                <th>Name</th>
                <th class="span2"></th>                            
    		</tr>
    	</thead>
    	
        <tbody>
            <?php foreach ($permissions as $permission): ?>		

            <tr>
                <td>
                    <?php echo $permission->id; ?>
                </td>
                <td>
                    <?php echo $permission->resource; ?>
                </td>
                <td>
    				<?php echo $permission->action; ?>
    			</td>
                <td>
                    <?php echo $permission->name; ?> <?php if($permission->description!=""): ?> (<?php echo $permission->description; ?>) <?php endif; ?>
                </td>
    			<td>
                    <div class="btn-group">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            Action
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><?php echo Html::anchor('admin/users/permissions/edit/'.$permission->id, 'Edit'); ?></li>
                            <li><?php echo Html::anchor('admin/users/permissions/delete/'.$permission->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?></li>
                        </ul>
                    </div>
    			</td>
    		</tr>
        <?php endforeach; ?>	
        </tbody>
    </table>

    <?php echo utf8_encode(html_entity_decode($pagination)); ?>

<?php else: ?>
    
    <p>No Users.</p>

<?php endif; ?>