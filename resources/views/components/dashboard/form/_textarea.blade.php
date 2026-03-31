@php
    $replacedName = replaceNameWithDots($name);
    $title = __('label.'.($title ?? $replacedName));
    $labelId = empty($id) ? $name.'_'.rand() : $id;
@endphp
@if(!isset($noLabel))
<label for="{{$labelId}}" class="control-label">{{ $title }}</label>
@endif

<textarea
       id="{{$labelId}}"
       @isset($dataName)
           data-name="{{$dataName}}"
       @endisset
       @isset($autocomplete) autocomplete="off" @endisset
       @if(!empty($readonly)) readonly @endif
       @if(!empty($disabled)) disabled @endif
       placeholder="{{ $title }}"
       name="{{ $name ?? '' }}"
       class="form-control {{ $class ?? '' }}"
       cols="{{ $cols ?? 30 }}" rows="{{ $rows ?? 10 }}"
>{{ $value ?? '' }}</textarea>
<x-dashboard.form._error :name="$replacedName" />
