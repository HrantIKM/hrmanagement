<form action="{{ $action ?? '' }}" method="{{ $formMethod ?? 'post'}}" id="{{ $id ?? '__form__request' }}"  @if($viewMode == 'show') class="show-mode" @endif>

<div class="card-header sticky-top">
    <div class="form-bottom-buttons">
        <a href="{{ $indexUrl }}" class="btn btn-secondary ms-2">{{ __('button.cancel') }}</a>

        @if($viewMode != 'show')
        <x-dashboard.form._loader_btn disabled
            class="form__request__send__btn ms-2"
            text="{{ $textBtn ?? 'save' }}"
        />
        @endif
    </div>
</div>

<div class="card-body loading-content">
    @method($method ?? 'post')
    @csrf

    <?php
    $tabId = $attributes['id'] ?? '__mls__tabs';
    $tabLocalId = \Illuminate\Support\Str::singular($tabId);
    ?>

    <div class="bordered-tabs {{ $attributes['class'] ?? '' }}">
        <ul class="nav nav-tabs" id="{{$tabId}}" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="general" data-bs-toggle="tab" href="#{{$tabLocalId}}__general" role="tab" aria-controls="home"
                   aria-selected="true">{{__('__dashboard.tab.general')}}</a>
            </li>
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <li class="nav-item">
                    <a class="nav-link" id="{{$localeCode}}" data-bs-toggle="tab" href="#{{$tabLocalId}}__{{$localeCode}}" role="tab" aria-controls="home"
                       aria-selected="true">{{ $properties['name'] }}</a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content {{ $attributes['tabContentClass'] ?? '' }}" id="{{$tabId}}__content">
            <div class="tab-pane fade show active" id="{{$tabLocalId}}__general" role="tabpanel" aria-labelledby="general">
                {{$generalTabData}}

                @isset($showStatus)
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <x-dashboard.form._show_status
                                    :value="$showStatus ?? ''"
                                />
                            </div>
                        </div>
                    </div>
                @endisset
            </div>

            @if(isset($customMlRender))
                {{$customMlRender}}
            @else
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <div class="tab-pane {{ $attributes['tabsClass'] ?? '' }} fade" id="{{$tabLocalId}}__{{$localeCode}}" role="tabpanel" aria-labelledby="{{$localeCode}}">

                        {{  $renderMlHtml($mlTabsData, $localeCode, $attributes['mlData'] ?: []) }}

                        @isset($mlTabsData->attributes['addCopyButtons'])
                        <div class="form-group">
                            <div class="copy-ml-buttons">
                                @foreach(getSupportedLocales() as $supportedLocal)
                                    @if($localeCode == $supportedLocal) @continue @endif
                                    <button type="button" class="btn btn-success copy-ml-info-btn" data-current-lang-code="{{$localeCode}}" data-to-lang-code="{{$supportedLocal}}" data-to-lang-code="{{$supportedLocal}}">{{trans('button.info.copy.to.'.$supportedLocal)}}</button>
                                @endforeach
                            </div>
                        </div>
                        @endisset
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

{{--<div class="card-footer">
    <div class="form-bottom-buttons">
        @isset($footer)
            {{ $footer }}
        @else
            <a href="{{ $indexUrl }}" class="btn btn-secondary ms-2">{{ __('button.cancel') }}</a>

            @if($viewMode != 'show')
            <x-dashboard.form._loader_btn disabled
                class="form__request__send__btn ms-2"
                text="{{ $textBtn ?? 'save' }}"
            />
            @endif
        @endisset
    </div>
</div>--}}

</form>

