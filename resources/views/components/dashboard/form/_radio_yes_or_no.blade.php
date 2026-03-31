@php
    $replacedName = replaceNameWithDots($name);
    $title = __('label.'.($title ?? $replacedName));
    $labelId = empty($id) ? $name.'_'.rand() : $id;

    $yesValue = '1';
    $noValue = '0';
@endphp

<div>
    <label class="control-label">{{$title}}</label>
</div>
<div class="form-check form-check-inline">
    <div class="custom-radio-block">
        <input type="radio"
               id="{{$labelId.'_yes'}}"
               @if(!empty($readonly)) readonly @endif
               @if(!empty($disabled)) disabled @endif
               @if(isset($checked) && $checked == $yesValue) checked @endif
               name="{{ $name ?? '' }}"
               value="{{$yesValue}}"
               class="custom-radio {{ $class ?? '' }}"
        >
        <label for="{{ $labelId.'_yes' }}" class="form-check-label">{{__('label.boolean.1')}}</label>
    </div>
</div>
<div class="form-check form-check-inline">
    <div class="custom-radio-block">
        <input type="radio"
               id="{{$labelId.'_no'}}"
               @if(!empty($readonly)) readonly @endif
               @if(!empty($disabled)) disabled @endif
               @if(isset($defaultNo) && !$checked) checked @endif
               @if(isset($checked) && $checked == $noValue) checked @endif
               name="{{ $name ?? '' }}"
               value="{{$noValue}}"
               class="custom-radio {{ $class ?? '' }}"
        >
        <label for="{{ $labelId.'_no' }}" class="form-check-label">{{__('label.boolean.0')}}</label>
    </div>
</div>
<x-dashboard.form._error :name="$replacedName" />
