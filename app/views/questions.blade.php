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
<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
	@include ('navbar')
	<div id="QuestionsTable"></div>
</div>
@stop