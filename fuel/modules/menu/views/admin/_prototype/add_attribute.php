<div class="form-group">
	<label for="eav[<?= (isset($key) ? $key : '__key__'); ?>]" class="control-label col-lg-2"><?= (isset($label) ? $label : '__label__'); ?></label>
            
	<div class="col-lg-10">
		<input type="text" value="<?= (isset($value) ? $value : '__value__'); ?>" class="form-control" name="eav[<?= (isset($key) ? $key : '__key__'); ?>]"> <span></span> 
	</div>
</div>
