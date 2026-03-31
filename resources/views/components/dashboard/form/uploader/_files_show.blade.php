<div class="__uploaded__files mt-3">
    @if($multiple && count($value))
        <h5>{{ __('label.uploaded_files') }}</h5>
        <div class="d-flex justify-content-start flex-wrap ">
            @foreach($value as $item)
                <input type="hidden" name="{{ $item->field_name }}[]" value="{{ $item->file_name }}">
                <x-dashboard.form.uploader._file_item :item="$item"/>
            @endforeach
        </div>
    @elseif(!$multiple)

        <input type="hidden" name="{{ $hiddenName ?? $value->field_name }}" value="{{ $value->file_name }}">

        <h5>{{ __('label.uploaded_file') }}</h5>
        <div class="d-flex justify-content-start flex-wrap ">
            <x-dashboard.form.uploader._file_item :item="$value"/>
        </div>
    @endif
</div>
