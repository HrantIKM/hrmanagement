<div class="core-multiple-inputs">
    <div class="multiple-group-content {{$class}}">
        @empty($multipleData)
            <div class="group-item" data-index="{{$index}}">
                <div class="row">
                    {{$slot}}
                </div>
            </div>
        @else

            @foreach($multipleData as $index => $groupData)
                <div class="group-item" data-index="{{$index}}">
                    <div class="row">
                        {{$renderHtml($slot,$groupData,$index)}}

                        @if($index)
                        <i class="flaticon-circle remove-multiple-item"></i>
                        @endif
                    </div>
                </div>
            @endforeach

        @endempty
    </div>

    <button type="button" class="btn btn-success core-multiple-button"> {{ __('button.add_more') }}</button>
</div>
