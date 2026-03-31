<div class="card-header">
    <div class="card-title">
        @isset($title)
            <h3 class="card-label">{{ $title }}</h3>
        @endisset
    </div>

    @isset($createRoute)
        @php
            $pageName = isset($addPageName) ? $subHeaderData['pageName'].'.' : '';
        @endphp
        <div class="ms-auto">
            <a href="{{ $createRoute }}" class="btn btn-create">
                <i class="flaticon2-plus me-2"></i>
                {{ __("{$pageName}button.create") }}
            </a>
        </div>
    @endisset
</div>
