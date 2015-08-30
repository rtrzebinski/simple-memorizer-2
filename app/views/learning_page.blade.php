@extends('layouts.main')

@section('head')
<script type='text/javascript'>
	$(document).ready(function() {
		// when 'show answer' button clicked
		$('#show_answer_button').click(function(event) {
			event.preventDefault();
			// hide 'show answer' button
			$('#show_answer_button').hide();
			// display answer
			$('#answer_div').removeClass('hidden');
			// set 'display_answer' HTTP parameter to true, so answer will be visible after page reloading
			$('#display_answer').val(true);
		});
		// map keyboard keys to buttons
		$("body").keyup(function(ev) {
			// ignore when text is being written
			if ($(ev.target).is('input, textarea, select')) {
				return;
			}
			// show answer on space click
			if (ev.which == 32) {
				// 32 = space
				$("#show_answer_button").click();
			}
			// mark answer as good on 'g' key click
			if (ev.which == 71) {
				// 71 = g
				$("#good_answer_button").click();
			}
			// mark answer as bad on 'b' key click
			if (ev.which == 66) {
				// 66 = b
				$("#bad_answer_button").click();
			}
			// show next question on 'b' key click
			if (ev.which == 78) {
				// 78 = n
				$("#next_question_button").click();
			}
		});
		F
	});
</script>
@stop

@section('content')

<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
	@include ('navbar')

	{{ Form::open(['class' => 'form', 'role' => 'form']) }}

	<label for="actions">Actions</label>
	<div id="actions">
		<input id='good_answer_button' class="btn btn-default btn-success btn-lg" type="submit" value="Good" name="answer_correctness">&nbsp;&nbsp;&nbsp;
		<input id='bad_answer_button' class="btn btn-default btn-danger btn-lg" type="submit" value="Bad" name="answer_correctness">&nbsp;&nbsp;&nbsp;
		<input id='next_question_button' class="btn btn-default btn-lg" type="submit" value="Next">&nbsp;&nbsp;&nbsp;
		<button id='show_answer_button' class="btn btn-default btn-lg @if($display_answer) hidden @endif">Show answer</button>&nbsp;&nbsp;&nbsp;
		<input type="hidden" name="user_question_id" value="{{{ $user_question_id }}}">
		<input type="hidden" name="display_answer" id="display_answer" value="{{{ $display_answer }}}">
	</div>
	<br>
	<div>
		<label for="question">Question</label>
		<textarea id="question" class="form-control" name="question" rows="3">{{{ $question }}}</textarea><br>
	</div>
	<div id="answer_div" class="@if(!$display_answer) hidden @endif">
		<label for="answer">Answer</label>
		<textarea id="answer" class="form-control" name="answer" rows="4">{{{ $answer }}}</textarea><br>
		<input class="btn btn-default btn-lg" type="submit" value="Update question and answer" name="update">
	</div>

	{{ Form::close() }}

</div>

@stop