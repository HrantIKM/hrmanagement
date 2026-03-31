@php
    $randomNum = rand();
    $title ??= __('label.choose_crop');
@endphp

<div class="file__uploader__box">
    <span class="input-group-btn">
    <label for="{{$name}}" data-input="{{$name}}_thumbnail" data-preview="{{$name}}_holder" class="btn btn-primary text-white">
        <i class="flaticon2-photo-camera" style="color: #fff"></i> {{ $title }}</label>
    </span>

    <div class="preview-image d-none file__item__type__image position-relative">
        <img src="" class="cropped-img upload-file-img" alt="">
        <button class="__delete__file position-absolute" type="button">x</button>
    </div>

    <input id="{{$name}}" class="form-control d-none crop-fie-type"
           type="file" data-name="{{str_replace('[]', '', $name)}}"
           @isset($configKey) data-config-key="{{ $configKey }}" @endisset>
</div>
