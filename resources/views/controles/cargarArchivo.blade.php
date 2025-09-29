<div class="row">    
	<div class="col-xs-12 col-md-8 col-sm-8">  
		<label>{{ $label}}</label>
		<!-- image-preview-filename input [CUT FROM HERE]-->
		<div class="input-group image-preview-{{ $name }}">

			<input type="text" class="form-control image-preview-filename" disabled="disabled"> <!-- don't give a name === doesn't send on POST/GET -->
			<span class="input-group-btn">
				<!-- image-preview-clear button -->
				<button type="button" class="btn btn-danger image-preview-clear" style="display:none;">
					<span class="fa fa-window-close"></span> Quitar
				</button>
				<!-- image-preview-input -->
				<div class="btn btn-default image-preview-input">
					<span class="glyphicon glyphicon-folder-open"></span>
					<!--<span class="image-preview-input-title">Abrir</span>-->
					<input type="file" accept="image/png, image/jpeg, image/gif, application/pdf, application/vnd.ms-excel,application/msword,
					application/vnd.openxmlformats-officedocument.wordprocessingml.document" name="{{ $name }}"/> <!-- rename it -->
					<input type="hidden" class="image-nombre" name="image-nombre{{ $name}}" value="">
					<input type="hidden" name="image-preview-hid{{ $name}}" value="{{ old('image-preview-hid'. $name) }}">
				</div>
			</span>
		</div><!-- /input-group image-preview [TO HERE]-->
		<div class="text-danger">{!!$errors->first($name, '<small>:message</small>')!!}</div>
	</div>
</div>