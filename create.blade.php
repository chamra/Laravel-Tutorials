@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
	{{-- Colum to show the all data in the datebase --}}
		<div class="col-md-6">
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Adress</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody id="data">

				</tbody>
			</table>
		</div>
		<div class="col-md-6">
		{{-- Error alert box --}}
		<div class="alert alert-danger" style="display: none">
			ERROR
		</div>
		{{-- Form --}}
			{!! Form::open(['url' => 'student','method' => 'post','id'=>'formData']) !!}
    	<div class="form-group">
		<label for="email">Name</label>
			{!! Form::text('name',null,['class'=>'form-control','id'=>'name']); !!}
		</div>
		<div class="form-group">
			<label for="pwd">Address</label>
			{!! Form::text('address',null,['class'=>'form-control','id'=>'address']); !!}
			<input type="hidden" name="id" value="" disabled="" id="studentID">
		</div>
		<button type="submit" class="btn btn-default" id="create">Create</button>
		<button type="submit" class="btn btn-default" id="update" style="display: none">Update</button>
	{!! Form::close() !!}
		</div>
	</div>
	
</div>
@stop
@section('script')
<script type="text/javascript">
	$(document).ready(function($) {
		getAll();

		$('#create').click(function(event) {
			event.preventDefault();

			save();
		});

		$('#update').click(function(event) {
			event.preventDefault();
			id = $('#studentID').val();
			update(id);
		});

		$('#data').on('click','.btn-danger', function () {
			id = $(this).data('id');
			destroy(id);
		})

		$('#data').on('click','.btn-info', function () {
			id = $(this).data('id');
			edit(id);
		})
	});

//getting all rows from the database
	function getAll() {
		$("#data").empty();
		$.ajax({
			url: '{{ url('student') }}',
			type: 'GET',
		})
		.done(function(data) {
		$.each(data, function(index, val) {
			 $('#data').append('<tr>')
			 $('#data').append('<td>'+val.id+'</td>')
			 $('#data').append('<td>'+val.name+'</td>')
			 $('#data').append('<td>'+val.address+'</td>')
			 $('#data').append('<td><button class="btn btn-xs btn-danger" data-id="'+val.id+'">Delete</button><button class="btn btn-xs btn-info" data-id="'+val.id+'">Edit</button></td>')
			 $('#data').append('</tr>')
		});
		})
		.fail(function() {
			console.log("error");
		})
		
	}

//save and the form data to the database
	function save() {
		formData = $('#formData').serializeArray();

		$.ajax({
			url: '{{ url('student') }}',
			type: 'POST',
			dataType: 'JSON',
			data: formData,
		})
		.done(function() {
			document.getElementById("formData").reset();
			getAll();
		})
		.fail(function(data) {
			$('.alert').show();

			$.each(data.responseJSON, function(index, val) {
				 console.log(index+","+val);

				 $('input[name='+index+']').after('<span>'+val+'</span>');
			});
		})
		
	}

	function destroy(id) {
		$.ajax({
			url: '{{ url('student/delete') }}/'+id,
			type: 'DELETE',
			dataType: 'JSON',
			data: {_token: '{{ csrf_token() }}'},
		})
		.done(function() {
			getAll();
		})
		.fail(function() {
			$('alert').show();
		})
		
	}

//loading the relevent data form the database to the from to update that data
	function edit(id) {
		$.ajax({
			url: '{{ url('student/edit/') }}/'+id,
			type: 'GET',
		})
		.done(function(data) {
			$('#name').val(data.name);
			$('#address').val(data.address);
			$('#studentID').val(data.id);

			$('#create').hide();
			$('#update').show();
		})
		.fail(function() {
			console.log("error");
		})
		
	}

//sending the updated data to the database to save
	function update(id) {
		formData = $('#formData').serializeArray();
		$.ajax({
			url: '{{ url('student/update/') }}/'+id,
			type: 'POST',
			dataType: 'JSON',
			data: formData,
		})
		.done(function() {
			getAll();
			document.getElementById("formData").reset();
			$('#create').show();
			$('#update').hide();
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	}
</script>
@stop