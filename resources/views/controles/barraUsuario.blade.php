<a href="{{ url($form) }}" class="btn btn-primary"><span class="fa fa-file" title="Limpiar"></span></a>
@if(isset($modificar))
 	<button type="submit" class="btn btn-primary" title="Modificar"><span class="fa fa-edit" ></span></button>
 
 	<!--<button type="submit" class="btn btn-default" title="Eliminar"><span class="glyphicon glyphicon-remove"></span></button>-->
 	<a href="@yield('rutaFormularioDelete')" class="btn btn-danger" title="Consultar"><span class="fa fa-trash-alt"></span></a>
@else
 	<a href="{{ url($form) }}/show" class="btn btn-primary" title="Consultar"><span class="fa fa-search" ></span></a>
 	<button type="submit" class="btn btn-primary" title="Guardar"><span class="fa fa-save"></span></button>
@endif