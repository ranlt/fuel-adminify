<!-- Begin messages -->
<?php
	foreach (array('error', 'warning', 'success', 'info') as $type)
	{
		foreach(\Messages::instance()->get($type) as $message)
		{
		    $titles = array('error' => \Lang::get('general.messages.error'), 'warning' => \Lang::get('general.messages.warning'), 'success' => \Lang::get('general.messages.success'), 'info' => \Lang::get('general.messages.info') );
		    echo '<div class="alert alert-'.$message['type'].'"><button type="button" class="close" data-dismiss="alert">×</button><strong>'.$titles[$message['type']].'</strong>&nbsp;'.$message['body'].'</div>'."\n";
		}
	}
\Messages::reset();
?>
<!-- End of messages -->