<div class="me-2 position-relative file__item mb-2 file__item__type__{{$item->file_type}}">
    <button class="position-absolute __delete__file __confirm__delete__btn"
            data-event-name="deleteFileItemInDbEvent"
            data-url="{{ route('dashboard.files.delete', $item->id) }}"
            type="button"><i class="fas fa-times"></i></button>

    @if($item->file_type === \App\Models\File\Enums\FileType::IMAGE)
        <img src="{{ $item->file_url }}"  class="upload-file-img" alt="">
    @else
        <span class="mr-5 text-primary p-2">{{ $item->file_name }}</span>
    @endif
</div>
