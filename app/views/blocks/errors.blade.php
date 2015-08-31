{{-- $errors must be instance of Illuminate\Support\MessageBag --}}
@foreach($errors->getMessages() as $key => $value)
	@foreach ($value as $message)
		<div class="alert alert-danger" role="alert">{{ $message }}</div>
	@endforeach
@endforeach