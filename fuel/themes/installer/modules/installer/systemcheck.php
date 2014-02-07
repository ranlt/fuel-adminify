<?php if($php) : ?>
	<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span> You are using PHP 5.3.3 or higher.</div>
<?php else : ?>
	<div class="alert alert-danger"><span class="glyphicon glyphicon-remove"></span> Please update your PHP! We need PHP 5.3.3 or higher.</div>
<?php endif; ?>

<?php if($config == '0777') : ?>
	<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span> Config Directory has the correct permissions.</div>
<?php else : ?>
	<div class="alert alert-danger"><span class="glyphicon glyphicon-remove"></span> Config Directory has the permission <strong><?php echo $config; ?></strong>. Please change it to <strong>0777</strong>.</div>
<?php endif; ?>

<?php if($logs == '0777') : ?>
	<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span> Logs Directory has the correct permissions.</div>
<?php else : ?>
	<div class="alert alert-danger"><span class="glyphicon glyphicon-remove"></span> Logs Directory has the permission <strong><?php echo $logs; ?></strong>. Please change it to <strong>0777</strong>.</div>
<?php endif; ?>

<?php if($cache == '0777') : ?>
	<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span> Cache Directory has the correct permissions.</div>
<?php else : ?>
	<div class="alert alert-danger"><span class="glyphicon glyphicon-remove"></span> Cache Directory has the permission <strong><?php echo $cache; ?></strong>. Please change it to <strong>0777</strong>.</div>
<?php endif; ?>

<?php if($tmp == '0777') : ?>
	<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span> Tmp Directory has the correct permissions.</div>
<?php else : ?>
	<div class="alert alert-danger"><span class="glyphicon glyphicon-remove"></span> Tmp Directory has the permission <strong><?php echo $tmp; ?></strong>. Please change it to <strong>0777</strong>.</div>
<?php endif; ?>

<div class="pull-left">
	<?php echo \Html::anchor('installer/systemcheck', 'Re-Check', array('class' => 'btn btn-warning btn-lg')); ?>
</div>

<?php if($next_step) : ?>
<div class="pull-right text-right">
	<?php echo \Html::anchor('installer/settings', 'Go to next step', array('class' => 'btn btn-success btn-lg')); ?>
</div>
<?php endif; ?>
