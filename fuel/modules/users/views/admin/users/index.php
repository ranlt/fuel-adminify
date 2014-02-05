<?php if ($users): ?>
<table class="table table-striped table-bordered table-condensed">
	<thead>
		<tr>
			<th>Username</th>
                        <th>E-Mail</th>
                        <th>Permission</th>
                        <th>Last Login</th>
                        <th></th>
                        
		</tr>
	</thead>
	<tbody>
<?php foreach ($users as $user): ?>		<tr>
    
                         <td>
				<?php echo $user->username; ?>
			</td>
                        <td>
				<?php echo $user->email; ?>
			</td>
                        <td>
                                <?php
                                    
                                    $role_count = count($user->roles);
                                    $i = 0;
                                    
                                    foreach ($user->roles as $role) 
                                    {
                                        $is_last = ($i === $role_count - 1);
                                        echo ($is_last) ? $role->name : $role->name.", ";
                                        $i++;
                                    }
                                    
                                ?>
                            
                        </td>
                        
                        <td>
                                <?php echo $user->last_sign_in_at; ?>
                            
                        </td>
    
			<td>
                            <div class="btn-group">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                    Action
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><?php echo Html::anchor('admin/users/edit/'.$user->id, 'Edit'); ?></li>
                                    
                                   
                                    <?php if($user->is_confirmed == 0): ?>
                                        <li><?php echo Html::anchor('admin/users/activate/'.$user->id, 'Activate'); ?></li>
                                    <?php else: ?>
                                        <li><?php echo Html::anchor('admin/users/deactivate/'.$user->id, 'Deactivate'); ?></li>
                                    <?php endif; ?>
                                    
                                    <li><?php echo Html::anchor('admin/users/delete/'.$user->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?></li>

                                </ul>
                            </div>
                            
                            
				
			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

 <?php echo utf8_encode(html_entity_decode($pagination)); ?>

<?php else: ?>
<p>No Users.</p>

<?php endif; ?><p>

</p>
