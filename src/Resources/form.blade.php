@php
    $class_body = $class_body ?? "card-body";
@endphp
<div class='card'>
    <div class='card-header'>
        @empty($toggle)
            {!! title($title ?? "title do not implemented") !!}
        @else
        <div class='float-left'>{!! title($title ?? "title do not implemented") !!}</div>
        <div class='float-right'>
            <a href='javascript:void(1)'
                onclick='console.log($(this).parent().parent().parent().find(".{{str_replace(" ", ".", $class_body)}},.card-footer").slideToggle())'
                class='btn btn-secondary btn-sm'>
                <i class="fas fa-caret-down"></i>
            </a>
        </div>
        @endif
    </div>
    <div class='{{ $class_body }}' style='display:{{ empty($toggle) ? "block" : "none" }}'>
        @empty($template_form)
            {!! form($form) !!}
        @else
            @include($template_form, ['form' => $form])
        @endif
    </div>
</div>
