
<form id="score-form" class="form-horizontal" method="POST" action="{{ url('game/save-score') }}">
{!! csrf_field() !!}
{{ method_field('PUT') }}

	<div class="card shadow m-t-3 scrolling-content">
		<table class="table table-hover table-striped table-large text-small text-xs-left">
			<thead style="background: #f5f5f5;">
				<tr>
					<th>#</th>
					@foreach(array_keys($game->getTeams()) as $team)
						<th>
							 {!! $game->getTeams()[$team][0] == $game->users[(count($game->data['scores']) - 1) % 4] ?
							 	'<i class="fa fa-random text-primary" aria-hidden="true" title="Shuffle"></i>'
							 	: null !!}
							 {{ $game->getTeams()[$team][0]->first_name }} & {{ $game->getTeams()[$team][1]->first_name }}
							 {!! $game->getTeams()[$team][1] == $game->users[(count($game->data['scores']) - 1) % 4] ?
								 '<i class="fa fa-random text-primary" aria-hidden="true" title="Shuffle"></i>'
							 	: null !!}
							 <br/>
							 {{ $team }}
						</th>
					@endforeach
				</tr>
			</thead>

			<tbody class="unselectable touchable">
				@foreach($game->data['scores'] as $round => $value)
					<tr {!! $round < count($game->data['scores']) ? 'class="clickable-row"' : '' !!}
						data-href="{{ url('game/round/' . $round) }}">
						<td class="td-fixed"><strong>{{ $round }}</strong></td>

						@foreach(array_keys($game->getTeams()) as $team)
							@if($round == count($game->data['scores']) && Auth::user() == $game->user)
								<td>
									<div class="form-inline">
										<input name="{{ $team }}" class="form-control" style="width: 100px;" min="0" max="162" type="number" inputmode="numeric" pattern="[0-9]*"
										placeholder="0" autofocus="autofocus" autocomplete="off">
										<input name="{{ $team }}-roem" class="form-control" style="width: 100px;" min="0" step="10" type="number" inputmode="numeric" pattern="[0-9]*"
										placeholder="Roem" autofocus="autofocus" autocomplete="off">
									</div>
								</td>
							@else
								<td>
									<span style="margin-right: 10px;">
										{{ $game->data['scores'][$round][$team] }}
									</span>
									@if ($game->data['scores'][$round][$team . '-roem'] > 0)
									<span class="text-success" style="width: 100px;">
										<b>{{ $game->data['scores'][$round][$team . '-roem'] }} roem</b>
									</span>
									@endif
								</td>
							@endif
						@endforeach

					</tr>
				@endforeach

				@if(count($game->data['scores']) > 1)
					<tr style="background: #f5f5f5;">
						<td></td>
						@foreach(array_keys($game->getTeams()) as $team)
							<td>
								<strong>{{ $team }}</strong>
								<br/>
								{{ $game->getTotalKlaverjassenScores()[$team] }}
								@if($game->getTotalKlaverjassenScores()[$team . '-roem'] > 0)
									+
									<span class="text-success"><b>{{ $game->getTotalKlaverjassenScores()[$team . '-roem'] }}</b></span> = &emsp;
									<b>{{ $game->getTotalKlaverjassenScores()[$team] + $game->getTotalKlaverjassenScores()[$team . '-roem'] }}</b>
								@endif
							</td>
						@endforeach
					</tr>
				@endif
			</tbody>
		</table>
	</div>

	<div class="container">
		<div class="row">
		@include('errors.feedback')
			@if(Auth::user() == $game->user || Auth::user()->is_admin)
				<button class="btn btn-primary-outline" type="submit">Enter</button>
			@endif
			<button style="display: inline-block;" class="btn btn-danger-outline center-block" type="button" data-toggle="modal" data-target="#modal-delete">Stop</button>
		</div>
	</div>

</form>

@include('content.game.partials.playing.delete')

@push('scripts')
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".clickable-row").click(function() {
			window.document.location = $(this).data("href");
		});

		var wij = $("[name='Wij']");
		var zij = $("[name='Zij']");
		wij.bind('keyup change', function(e) {
			if (this.value <= 162) {
				zij.val(162 - this.value);
			}
		});
		zij.bind('keyup change', function(e) {
			if (this.value <= 162) {
				wij.val(162 - this.value);
			}
		});

		$("#score-form").submit(function() {
			var message = "The scores don't add up to 162.";
			var submit = (+wij.val() + +zij.val() == 162);

			content = (
				'<div class="alert alert-warning" role="alert">' +
				message	+
				'</div>'
			);

			if (!submit) {
				document.getElementById("feedback").innerHTML = content;
			} else {
				document.getElementById("feedback").innerHTML = null;
			}
			return submit;
		});
	});
</script>
@endpush

