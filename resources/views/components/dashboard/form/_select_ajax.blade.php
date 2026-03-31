@php
    $replacedName = replaceNameWithDots($name);
    $title = __('label.'.($title ?? $replacedName));
    $labelId = empty($id) ? $name.'_'.rand() : $id;
@endphp
@if(!isset($noLabel))
    <label for="{{$labelId}}" class="control-label">{{ $title }}</label>
@endisset
<select name="{{ $name }}"
        id="{{$labelId}}"
        class="form-control select2 select2-ajax {{ $class ?? '' }}"
        data-min-input-length="2"
        @isset($dataMode) data-mode="{{$dataMode}}" @endisset
        @isset($multiple) multiple data-is-multiple @endisset
        @isset($url) data-url="{{$url}}" @endisset
        @if(isset($disabled) && $disabled) disabled @endisset
        @if(isset($template) && $template) data-template="{{$template}}" @endif
        @if(isset($selectionTemplate) && $selectionTemplate) data-selection-template="{{$selectionTemplate}}" @endif
>
    @if(isset($multiple) && isset($data))
        @foreach($data as $key => $item)
            <option value="{{ $item->id }}" selected>{{$item->name}}</option>
        @endforeach
    @else
        @if(isset($data))
            <option value="{{ $data->id }}" selected>{!! $data->formatted_name ?? $data->name ??  $data->slug !!} </option>
        @endif
    @endif
</select>
<x-dashboard.form._error :name="$replacedName" />
