@if(!empty($filter))

@php
    $disable = true;
    foreach(array_keys($filter) as $key){
        if (request($key)) {
            $disable = false;
        }
        break;
    }
@endphp
<form>
    <div class='card'>
        <div class='card-header'>
            <div class='float-left'>{!! title($title ?? "title do not implemented") !!}</div>
            <div class='float-right'>
                <a href='javascript:void(1)'
                    onclick='console.log($(this).parent().parent().parent().find(".card-body,.card-footer").slideToggle())'
                    class='btn btn-secondary btn-sm'>
                    <i class="fas fa-caret-down"></i>
                </a>
            </div>
        </div>
        <div class='card-body' style="display:{{!$disable ? "block" : "none"}}">
            @foreach ($filter as $key => $value)
                @if(substr($key, 0, 7) == 'request')
                    {!! Form::hidden(substr($key, 8), request(substr($key, 8)), ['class' => 'form-control m-input']) !!}
                @else
                    <div class='form-group'>
                        @if(!is_array($value))
                            {!! Form::label($key, __($value)) !!}
                            {!! Form::text($key, request($key), ['class' => 'form-control m-input']) !!}
                        @else
                            {!! Form::label($key, __($value['label'])) !!}
                            @switch($value['type'])
                                @case('date')
                                {!! Form::date($key, request($key), ['class' => 'form-control m-input']) !!}
                                @break
                                @default
                                {!! Form::text($key, request($key), ['class' => 'form-control m-input']) !!}
                            @endswitch
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
        <div class='card-footer' style="display:{{!$disable ? "block" : "none"}}">
            <button class='btn btn-outline-primary'>{!! __('Filter') !!}</button>
        </div>
    </div>
</form>
@endif
