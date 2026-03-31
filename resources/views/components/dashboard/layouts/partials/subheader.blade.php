<div class="subheader flex-wrap">
    <div class="subheader-left pt-2 pb-2">
        <h3 class="subheader-left-title">{{ isset($subHeaderData['pageName']) ? __('page.'.$subHeaderData['pageName'].'.title') : __('__dashboard.title') }}</h3>
        <span class="subheader-left-separator"></span>
        <div class="subheader-breadcrumbs">
{{--            <a href="#" class="breadcrumbs-link">{{ __('__dashboard.'.($sub_header_data['pageName'] ?? '').'.description') }}</a>--}}
        </div>
    </div>
</div>
