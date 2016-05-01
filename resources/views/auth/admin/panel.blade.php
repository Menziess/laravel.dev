<div class="card card-block card-inverse" style="background-color: #333; border-color: #333;">
	@if($user->is_active)
	<h4 class="card-title">Active</h4>
	<p class="card-text">User #{{ $user->id }} is active and is able to login.</p>
	@else
	<h4 class="card-title">Inactive</h4>
	<p class="card-text">User #{{ $user->id }} is inactive and is unable to login. This content of this user is not vsible for other users.</p>
	@endif
	<div id="modal-delete" class="modal fade">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
					<h4 class="modal-title">Delete account</h4>
				</div>
				<div class="modal-body">
					<p>
						Deleting this account will also remove all associated private data, are you sure? :(
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<a href="{{ url('/user/delete/' . $user->getKey()) }}" class="btn btn-danger" role="button">Delete</a>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<div class="btn-group btn-group-justified">
	<a href="{{ url('/admin/activate/' . $user->getKey()) }}" class="btn btn-success-outline" role="button">Activate</a>
	<a href="{{ url('/admin/deactivate/' . $user->getKey()) }}" class="btn btn-warning-outline" role="button">Deactivate</a>
	</div>
</div>

<div class="card card-block card-inverse" style="background-color: #333; border-color: #333;">
	<h4 class="card-title">Hard delete</h4>
	<p class="card-text">User #{{ $user->id }} is currently not deleted. A deleted user will lose all related data, this data can't be restored.</p>
	<a data-toggle="modal" data-target="#modal-delete" href="#" class="btn btn-danger">Delete</a>
</div>
