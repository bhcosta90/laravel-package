<div class='card-header'>
    <div class='float-left'><h5 class="pt-1 pb-0 title">{{$title}}</h5></div>
    @empty(!$register)
        <div class='float-right'>
            <a href='{{$register}}' class='btn btn-light'>{{ __($textRegister ?? "New register") }}</a>
        </div>
    @endif
</div>
