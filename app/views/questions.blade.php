@extends('layouts.main')

@section('head')

<script type="text/javascript">
	$(document).ready(function() {

		$('#QuestionsTable').jtable({
			title: 'Questions',
			paging: true, //Enable paging
			pageSize: 10, //Set page size (default: 10)
			sorting: true, //Enable sorting
			defaultSorting: 'id ASC', //Set default sorting
			actions: {
				listAction: '{{ route("list_questions") }}',
				deleteAction: '{{ route("delete_questions") }}',
				updateAction: '{{ route("update_questions") }}',
				createAction: '{{ route("create_questions") }}'
			},
			fields: {
				id: {
					key: true,
					create: false,
					edit: false,
					list: false
				},
				question: {
					title: 'Question'
				},
				answer: {
					title: 'Answer'
				},
				percent_of_good_answers: {
					title: '% of good answers',
					create: false,
					edit: false
				}
			}
		});

		$('#QuestionsTable').jtable('load');
	});

</script>
@stop

@section('content')
<h3>this is QUESTIONS page</h3>
<h3>Hello, {{{ $user->name }}}</h3>
<h3><a href="{{{ route('overview') }}}">overview</a></h3>
<div id="QuestionsTable"></div>
@stop