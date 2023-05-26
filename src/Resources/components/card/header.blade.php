<div class='card-header'>
    <div class='float-left'><h5 class="pt-1 pb-0 title">{{$title}}</h5></div>
    @empty(!$register)
        <div class='float-right'>
            @switch($typeRegister)
                @case('submit')
                    <button class='btn btn-light'>{{ __($textRegister ?? "New register") }}</button>
                    @break
                @default
                <a href='{{$register}}' class='btn btn-light'>{{ __($textRegister ?? "New register") }}</a>
            @endswitch
        </div>
    @endif
    @if($open)
    <div class='float-right'>
        <a href='javascript:void(1)'
            onclick='console.log($(this).parent().parent().parent().find("{{$open}}").slideToggle())'
            class='btn btn-secondary btn-sm'>
            <i class="fas fa-caret-down"></i>
        </a>
    </div>
    @endif
</div>
