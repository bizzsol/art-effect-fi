@if(isset($users[0]))
@foreach($users as $key => $user)
<option value="{{ $user->id }}">{{ $user->name }}</option>
@endforeach
@endif