@php
    $randomNum = rand();
    $title = __('label.'.(str_replace('[]', '', $title ?? $name)));
    $hasCrop = $crop ?? false;
@endphp
<label for="{{ $title }}.{{ $randomNum }}" class="control-label">{{ $title }}</label>
<div class="file__uploader__box {{$hasCrop ? 'with-crop' : ''}}">
    <span class="input-group-btn">
    <label for="{{$name}}.{{ $randomNum }}" data-input="{{$name}}_thumbnail" data-preview="{{$name}}_holder"
           class="btn btn-primary text-white">
        <i class="flaticon2-photo-camera" style="color: #fff"></i> {{__('Choose')}} {{ $title }}</label>
    </span>
    <input id="{{$name}}.{{ $randomNum }}" class="form-control d-none temp-file-type"
           type="file" data-name="{{str_replace('[]', '', $name)}}"
           @isset($configKey) data-config-key="{{ $configKey }}" @endisset
           @isset($multiple) multiple @endisset
           data-has-crop="{{$hasCrop}}">

    <div class="hidden-file-inputs"></div>

    <div class="border-dark d-flex justify-content-start align-items-center flex-wrap __file__list__default mt-2"></div>

    <div class="error-messages-block mt-4" style="display: none">
        <div class="alert alert-danger" role="alert">
            <ul class="m-0"></ul>
        </div>
    </div>

    <x-dashboard.form._error :name="replaceNameWithDots($name)"></x-dashboard.form._error>

    <div class="d-flex justify-content-start flex-wrap __file__list"></div>

    @if(isset($value) && $value)
        <x-dashboard.form.uploader._files_show :value="$value ?? ''"
                                               :multiple="$multiple ?? null"
                                               :hidden-name="$hiddenName ?? null"
                                               fieldName="str_replace('[]', '', str_replace('_', ' ', $name))"
        />
    @endif
</div>
