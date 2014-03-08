<a href="#" class="list-group-item list-group-item-info">
	<button type="button" class="close" style="float: none;" data-dismiss="alert" aria-hidden="true">&times;</button>
	<div class="form-group">
		<label for="params[__id__][name]" class="control-label col-lg-2">Nom</label>
	            
		<div class="col-lg-10">
			<input type="text" value="<?php if(isset($name)) echo $name; ?>" class="form-control" name="params[__id__][name]"> <span></span> 
		</div>
	</div>
	<div class="form-group">
		<label for="params[__id__][value]" class="control-label col-lg-2">Valeur</label>
	            
		<div class="col-lg-10">
			<input type="text" value="<?php if(isset($value)) echo $value; ?>" class="form-control" name="params[__id__][value]"> <span></span> 
		</div>
	</div>
</a>