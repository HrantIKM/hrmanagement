@php
    $target = 'searchFormCollapse_'.rand();
    $addCollapse = !isset($withoutCollapse);
    $showCollapse = (bool)array_filter($_GET);
@endphp

<div class="text-end">
    @if($addCollapse)
    <button class="btn open__search__form__btn" type="button" data-bs-toggle="collapse"
            data-bs-target="#{{$target}}"
            aria-expanded="{{$showCollapse ? 'true' : 'false'}}"
            aria-controls="{{$target}}">
        <span class="text-search"><img class="me-2" src="/img/filter-icon.svg" /> {{__('button.filters')}}</span>
        <span class="close-search"><i class="fa fa-times"></i></span>
    </button>
    @endif
</div>

<div class="datatable-search-collapse">
    <div class="{{$addCollapse ? 'collapse' : ''}} {{$showCollapse ? 'show' : ''}}"
         id="{{$target}}">
        <form class="ms-auto" id="dataTable__search__form">
            <div class="row p-0">
                {{ $slot }}
            </div>
            <div class="text-end">
                <div class="spinner-border loading__form__icon" role="status"></div>
                <button class="btn btn-primary search__form__btn">
                    {{ __('button.search') }}
                </button>
                <button class="btn btn-danger reset__form__btn ms-2" type="button">
                    {{__('button.reset')}}
                </button>
            </div>
        </form>
    </div>
</div>
