@php
    $name = 'show_status';
    $randomNum = rand();
    $title = __('label.'.$name);
    $selectedValue = $value ?? \App\Models\Base\Enums\ShowStatus::ACTIVE;
@endphp

<label for="{{ $name }}_{{ $randomNum }}" class="control-label">{{ $title }}</label>
<select name="{{ $name }}"
        id="{{empty($id) ? $name.'_'.$randomNum : $id}}"
        class="form-control default-search{{ $class ?? '' }}" class="default-search"
>
    @if(isset($showAllOption))
        <option value="">{{ __('__dashboard.select.option.all') }}</option>
    @endif
    @foreach(\App\Models\Base\Enums\ShowStatus::FOR_SELECT as $item)
        <option value="{{ $item }}"
                @if($selectedValue == $item) selected @endif
        >
            {{ __('__dashboard.select.option.show_status_'.$item) }}
        </option>
    @endforeach
</select>
<x-dashboard.form._error :name="$name"></x-dashboard.form._error>

