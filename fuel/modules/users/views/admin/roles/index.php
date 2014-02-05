<?php if ($roles): ?>
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th class="span1">ID</th>
                <th>Role</th>
                <th class="span2"></th>                            
    		</tr>
    	</thead>
    	
        <tbody>
            <?php foreach ($roles as $role): ?>		

            <tr>
                <td>
                    <?php echo $role->id; ?>
                </td>
                <td>
    				<?php echo $role->name; ?> (<?php echo $role->description; ?>)
    			</td>
    			<td>
                    <div class="btn-group">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            Action
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><?php echo Html::anchor('admin/users/roles/edit/'.$role->id, 'Edit'); ?></li>
                            <li><?php echo Html::anchor('admin/users/roles/delete/'.$role->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?></li>
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