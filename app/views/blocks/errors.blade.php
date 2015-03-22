{{-- $errors must be instance of Illuminate\Support\MessageBag --}}
@foreach($errors->getMessages() as $key => $value)
	@foreach ($value as $message)
		{{{ $message }}} </br>
	@endforeach
@endforeach