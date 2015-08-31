@extends('layouts.main')

@section('head')

<script type="text/javascript">
	$(document).ready(function() {

		$('#QuestionsTable').jtable({
			title: 'Questions',
			paging: true, //Enable paging
			pageSize: 100, //Set page size (default: 10)
			sorting: true, //Enable sorting
			defaultSorting: 'id ASC', //Set default sorting
			selecting: true, //Enable selecting
			multiselect: true, //Allow multiple selecting
			selectingCheckboxes: true, //Show checkboxes on first column
			selectOnRowClick: false, //Enable this to only select using checkboxes
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
					title: 'Question',
					type: 'textarea'
				},
				answer: {
					title: 'Answer',
					type: 'textarea'
				},
				percent_of_good_answers: {
					title: '% of good answers',
					create: false,
					edit: false
				}
			}
		});

		// load jtable user interface
		$('#QuestionsTable').jtable('load');

		//Delete selected rows
		$('#DeleteAllButton').button().click(function() {
			var $selectedRows = $('#QuestionsTable').jtable('selectedRows');
			$('#QuestionsTable').jtable('deleteRows', $selectedRows);
		});
	});

</script>
@stop

@section('content')
<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
	@include ('navbar')
	<div id="QuestionsTable"></div>
	<br/>
	<a href='{{ route('questions_export') }}' class='btn btn-default btn-sm'>Export</a>
	<a href='{{ route('questions_import') }}' class='btn btn-default btn-sm'>Import</a>
	<button class='btn btn-default btn-sm' id='DeleteAllButton'>Delete selected</button>
</div>
@stop