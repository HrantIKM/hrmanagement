<?php
$size ??= 'md'; // sm, lg, xl
$cancelBtnKey ??= 'cancel';
$saveBtnKey ??= 'crop';
$saveBtnClass ??= 'success';
$modalId = $id . "Modal";
?>

<div class="modal crop-modal" id="{{$modalId}}" tabindex="-1" role="dialog" aria-labelledby="{{$modalId}}Label"
     aria-modal="true" @isset($static) data-bs-backdrop="static" @endisset>
    <div class="modal-dialog modal-{{$size}} modal-dialog-scrollable" role="dialog">
        <div class="modal-content border-0">

            <div class="modal-header">
                @isset($header)
                    {{ $header }}
                @else
                    <h5 class="modal-title">@isset($headerText){{__('__dashboard.modal.title.'.$headerText)}}@endisset</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                @endisset
            </div>

            <div class="modal-body">
                <div class="crop-img-container"></div>
            </div>

            <div class="modal-footer pb-2">
                @isset($footer)
                    {{ $footer }}
                @else

                    <button type="button" class="btn btn-secondary py-2 cancel-btn"
                            data-bs-dismiss="modal">{{__('button.'.$cancelBtnKey)}}</button>

                    <button type="button"
                            class="btn btn-{{$saveBtnClass}} py-2 save-btn">{{__('button.'.$saveBtnKey)}}</button>

                @endisset
            </div>
        </div>
    </div>
</div>
