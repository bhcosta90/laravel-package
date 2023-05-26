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
    <x-card>
        <x-card-header :title="$title ?? 'title do not implemented'" open=".card-body, .card-footer" />
        <x-card-body show=0>
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
        </x-card-body>
        <div class='card-footer' style="display:{{!$disable ? "block" : "none"}}">
            <button class='btn btn-outline-primary'>{!! __('Buscar') !!}</button>
        </div>
    </x-card>
</form>
@endif
