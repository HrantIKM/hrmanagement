<?php
    $tabId = $attributes['id'] ?? '__mls__tabs';
    $tabLocalId = \Illuminate\Support\Str::singular($tabId);
?>

<div class="bordered-tabs {{ $attributes['class'] ?? '' }}">
    <ul class="nav nav-tabs" id="{{$tabId}}" role="tablist">
        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <li class="nav-item">
                <a class="nav-link @if(!$loop->index) active @endif" id="{{$localeCode}}" data-bs-toggle="tab" href="#{{$tabLocalId}}__{{$localeCode}}" role="tab" aria-controls="home"
                   aria-selected="true">{{ $properties['name'] }}</a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content {{ $attributes['tabContentClass'] ?? '' }}" id="{{$tabId}}__content">
        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <div class="{{ $attributes['tabsClass'] ?? '' }} tab-pane fade @if(!$loop->index) show active @endif" id="{{$tabLocalId}}__{{$localeCode}}" role="tabpanel" aria-labelledby="{{$localeCode}}">
                {{  $renderHtml($slot, $localeCode, $attributes['mlData'] ?? '') }}
            </div>
        @endforeach
    </div>
</div>
