<div class="{{ $div }}">
    <p class="mb-0 font-weight-bold"><label for="{{ $slug }}">{{ __($text) }}:</label> {!! isset($required) && $required ? '<span class="text-danger text-capitalize">*</span>' : '' !!}</p>
    <div class="input-group input-group-md mb-3 d-">
        <input type="text" name="{{ $slug }}" id="{{ $slug }}" class="form-control rounded" aria-label="Large" placeholder="{{__($placeholder)}}" aria-describedby="inputGroup-sizing-sm" value="{{ old($slug, (isset($value) ? $value : '')) }}" {{ isset($required) && $required ? 'selected' : '' }}>
    </div>
</div>