@if(isset($locations[0]))
@foreach($locations as $key => $location)
<option value="{{ $location->id }}">{{ $location->code }} :: {{ $location->name }}</option>
@endforeach
@endif