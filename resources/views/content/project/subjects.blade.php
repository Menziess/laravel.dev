@extends('layouts.app')

@section('content')

	@include('content.subject.partials.owner')

	@include('content.subject.partials.users')

	@include('content.subject.partials.organizations')

	@push('scripts')
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(".clickable-row").click(function() {
				window.document.location = $(this).data("href");
			});
		});
	</script>
	@endpush

@endsection