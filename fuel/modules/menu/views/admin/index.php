<?= $menuBreadcrumb; ?>

<?php if(empty($menus)): ?>
	<p><?= __('menu.none'); ?></p>
<?php else: ?>
	<div id="alerts"></div>

	<div class="table-responsive">
        <table class="table table-striped table-bordered table-hover <?php if(isset($menuParent)) echo 'sortable'; ?>" data-toggle="datatable" id="table-menu">
            <thead>
                <tr>
	                <th></th>
                    <th><?= __('menu.table.id'); ?></th>
                    <th><?= __('menu.table.text'); ?></th>
                    <th><?= __('menu.table.slug'); ?></th>
                    <th><?= __('menu.table.submenu'); ?></th>
                    <th><?= __('menu.table.links'); ?></th>
                    <th><?= __('menu.table.actions'); ?></th>
                </tr>
            </thead>
            <tbody>
            	<?php foreach($menus as $menu): ?>
                <?php $menuLang = \LbMenu\Helper_Menu::getLang($menu); ?>
				<tr id="object-<?= $menu->id; ?>" data-id="<?= $menu->id; ?>">
					<td>
                        <?php if($menu->nbLink > 0 || $menu->nbSub > 0): ?>
                            <span class="fa fa-list-ul fa-lg fa-fw"></span>
                        <?php else: ?>
                            <span class="fa fa-link fa-lg fa-fw"></span>
                        <?php endif; ?>
                    </td>
					<td><?= $menu->id; ?></td>
                    <td><?= $menuLang->text; ?></td>
					<td><?= $menu->slug; ?></td>
					<td><?= $menu->nbSub ?></td>
					<td><?= $menu->nbLink ?></td>
					<td>
						<a href="<?= \Router::get('menu_admin_delete', array('id' => $menu->id)); ?>" class="btn btn-danger btn-circle"><i class="fa fa-trash-o fa-lg"></i></a>
						<a href="<?= \Router::get('menu_admin_edit', array('id' => $menu->id)); ?>" class="btn btn-info btn-circle"><i class="fa fa-pencil fa-lg"></i></a>
                        <a href="<?= \Router::get('menu_admin_submenu', array('id' => $menu->id)); ?>" class="btn btn-warning btn-circle"><i class="fa fa-level-down fa-lg"></i></a>
                    </td>
				</tr>
            	<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php endif; ?>

<a href="<?= \Router::get('menu_admin_add'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> <?= __('menu.action.create'); ?></a>

<?php \Debug::dump(\Router::get('menu_admin_add') ); ?>

<?php if(isset($menuParent)): ?>
	<a href="<?= \Router::get('menu_admin_add_to_parent', array('parent' => $menuParent->id)); ?>" class="btn btn-info"><i class="fa fa-plus"></i> <?= __('menu.action.add_link'); ?></a>
<?php endif; ?>


