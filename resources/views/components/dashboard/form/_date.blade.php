@php
    $replacedName = replaceNameWithDots($name);
    $title = __('label.'.($title ?? $replacedName));
    $labelId = empty($id) ? $name.'_'.rand() : $id;

    if(empty($dateTime)){
        $backendValue = formatDateForBackend($value ?? '');
    }else{
       $backendValue = formatDateTimeForBackend($value ?? '');
    }
@endphp

@if(!isset($noLabel))
    <label for="{{ $labelId }}" class="control-label">{{ $title }}</label>
@endif

<input type="text"
       id="{{$labelId}}"
       autocomplete="off"
       @if(!empty($readonly)) readonly @endif
       @if(!empty($disabled)) disabled @endif
       @if(!isset($noPlaceholder))
       placeholder="{{ $title }}"
       @endif
       value="{{ $value ?? '' }}"
       class="form-control {{ $class ?? '' }}">
<input type="hidden" class="backend-date-value" name="{{ $name ?? '' }}" value="{{ $backendValue }}">
<x-dashboard.form._error :name="$replacedName" />
