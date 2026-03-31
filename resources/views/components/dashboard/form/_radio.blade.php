@php
    $replacedName = replaceNameWithDots($name);
    $title = __('label.'.($title ?? $replacedName));
    $labelId = empty($id) ? $name.'_'.rand() : $id;
@endphp

<div class="custom-radio-block mb-2">
    <input type="radio"
           id="{{$labelId}}"
           @if(!empty($readonly)) readonly @endif
           @if(!empty($disabled)) disabled @endif
           @if(isset($checked) && $checked) checked @endif
           name="{{ $name ?? '' }}"
           value="{{ $value ?? '' }}"
           class="custom-radio {{ $class ?? '' }}"
    >
    <label for="{{ $labelId }}" class="form-check-label">{{ $title }}</label>
</div>
<x-dashboard.form._error :name="$replacedName" />

