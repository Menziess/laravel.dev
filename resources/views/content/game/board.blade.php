@extends('layouts.app')

@section('content')

	<div class="container">

		@if($game->started_at)

			@include('content.game.partials.playing')

		@else

			@include('content.game.partials.players')

		@endif

	</div>

@endsection