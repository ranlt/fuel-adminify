<h1><?= __('migration.migration.list'); ?></h1>
<hr>
<?php if (empty($migrationsVar)): ?>
    <?= __('migration.migration.empty'); ?>
<?php else: ?>
    <?php echo \Html::anchor('migrations/migrate/all/latest', __('migration.migrate.all'), $attributes = array('class' => 'btn btn-primary')); ?>
    <?php echo \Html::anchor('migrations/migrate/all/current', __('migration.migrate.all_current'), $attributes = array('class' => 'btn btn-info')); ?>
<hr>

<ul class="nav nav-tabs">
    <?php if(isset($migrationsVar['app'])): ?><li class="active"><a href="#app" data-toggle="tab">Core</a></li><?php endif; ?>
    <?php if(isset($migrationsVar['module'])): ?><li><a href="#module" data-toggle="tab">Modules</a></li><?php endif; ?>
    <?php if(isset($migrationsVar['package'])): ?><li><a href="#package" data-toggle="tab">Packages</a></li><?php endif; ?>
</ul>

<div class="tab-content">
    <?php foreach($migrationsVar as $type => $names): ?>
        <div class="tab-pane <?php if($type=='app') echo 'active'; ?>" id="<?= $type; ?>">
      <h2><?= ucfirst($type); ?></h2>

      <?php foreach($names as $name => $migrations): ?>

        <div class="well">
        <h3 class="pull-left"><?= ucfirst($name); ?></h3>

        <div class="pull-right">
            <?php echo \Html::anchor('migrations/migrate/'.$type.'_'.$name.'/latest', __('migration.migrate.all'), $attributes = array('class' => 'btn btn-primary')); ?>
            <?php echo \Html::anchor('migrations/migrate/'.$type.'_'.$name.'/current', __('migration.migrate.current'), $attributes = array('class' => 'btn btn-info')); ?>
        </div>

        <table class="table table-striped" id="migration-table">
            <thead>
            <tr>
                <th><?= __('migration.migration.version'); ?></th>
                <th><?= __('migration.migration.name'); ?></th>
                <th><?= __('migration.migration.action'); ?></th>
            </tr>
            </thead>
            
            <tbody>
            <?php foreach ($migrations as $version => $migration): ?>
                <tr>
                    <td><?= $version; ?></td>
                    <td><?= $migration['file']; ?></td>
                    <td>
                        <?php if($migration['conflict']): ?>
                            <a href="#" class="btn btn-danger"><?= __('migration.migration.conflict'); ?></a>
                        <?php elseif($migration['done']): ?>
                            <?php echo \Html::anchor('migrations/migrate/'.$version.'_'.$type.'_'.$name, __('migration.migration.rollback'), $attributes = array('class' => 'btn btn-warning')); ?>
                        <?php else: ?>
                            <?php echo \Html::anchor('migrations/migrate/'.$version.'_'.$type.'_'.$name, __('migration.migration.migrate'), $attributes = array('class' => 'btn btn-success')); ?>
                        <?php endif; ?> 
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
      <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>
