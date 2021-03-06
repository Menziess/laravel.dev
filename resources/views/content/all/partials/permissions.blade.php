<div id="admin" class="card shadow card-block card-inverse" style="background-color: #333; border-color: #333;">

	<h4 class="card-title">Admin</h4>

	<p class="card-text">
		{{ ucfirst(class_basename($subject)) }} #{{ $subject->id }} is {!! $subject->is_admin
		? '<span class="text-success">admin</span> and can modify games.'
		: '<span class="text-warning">no admin</span> and is unable to modify games.' !!}
	</p>

	<div class="btn-group btn-group-justified">
	<form id="form-profile" class="form-horizontal" method="POST" action="{{ url('/admin/enable-admin' . '/' . $subject->getKey()) }}">
		{!! csrf_field() !!}
		{{ method_field('PUT') }}
		<button class="btn btn-success-outline" type="submit">Enable Admin</a>
	</form>
	</div>

	<div class="btn-group btn-group-justified">
	<form id="form-profile" class="form-horizontal" method="POST" action="{{ url('/admin/disable-admin' . '/' . $subject->getKey()) }}">
		{!! csrf_field() !!}
		{{ method_field('PUT') }}
		<button class="btn btn-warning-outline" type="submit">Disable Admin</a>
	</form>
	</div>

</div>

<div id="permissions" class="card card-block card-inverse" style="background-color: #333; border-color: #333;">

	<h4 class="card-title">Disable {{ class_basename($subject) }}</h4>
	<p class="card-text">
		{{ ucfirst(class_basename($subject)) }} #{{ $subject->id }} is {!! $subject->is_active
		? '<span class="text-success">active</span> and can be seen by other users.'
		: '<span class="text-warning">inactive</span> and is hidden for other users.' !!}
	</p>

	<div class="btn-group btn-group-justified">
	<form id="form-profile" class="form-horizontal" method="POST" action="{{ url('/admin/activate-' . lcfirst(class_basename($subject)) . '/' . $subject->getKey()) }}">
		{!! csrf_field() !!}
		{{ method_field('PUT') }}
		<button class="btn btn-success-outline" type="submit">Activate</a>
	</form>
	</div>

	<div class="btn-group btn-group-justified">
	<form id="form-profile" class="form-horizontal" method="POST" action="{{ url('/admin/deactivate-' . lcfirst(class_basename($subject)) . '/' . $subject->getKey()) }}">
		{!! csrf_field() !!}
		{{ method_field('PUT') }}
		<button class="btn btn-warning-outline" type="submit">Deactivate</a>
	</form>
	</div>
</div>

<div id="delete" class="card card-block card-inverse" style="background-color: #333; border-color: #333;">

	<div id="modal-delete" class="modal fade">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
					<h4 class="modal-title">Delete {{ class_basename($subject) }}</h4>
				</div>
				<div class="modal-body">
					<p>
						Deleting this {{ class_basename($subject) }} will also remove all associated private data, are you sure?
					</p>
				</div>
				<div class="modal-footer">
					<form id="form" class="form-horizontal" role="form" method="POST" action="{{ url('/' .lcfirst(class_basename($subject)) . '/delete/' . $subject->getKey()) }}">
					{!! csrf_field() !!}
					{{ method_field('DELETE') }}
					<button type="button" class="btn btn-secondary-outline" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-danger">Delete</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<h4 class="card-title">Delete</h4>

	<p class="card-text">Deleting {{ class_basename($subject) }}  #{{ $subject->id }} will also remove all associated private data.</p>

	@if ($errors->has(class_basename($subject)))
		<div class="alert alert-warning" role="alert">
			{{ $errors->first(class_basename($subject)) }}
		</div>
	@endif

	<div>
	<a data-toggle="modal" data-target="#modal-delete" href="#" class="btn btn-danger">Delete</a>
	</div>
</div>
