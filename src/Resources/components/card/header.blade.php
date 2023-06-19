<div class='card-header'>
    <div class='float-left float-start'><h5 class="pt-1 pb-0 title">{{$title}}</h5></div>
    @empty(!$register)
        <div class="float-right float-end">
            {!! $register->run() !!}
        </div>
    @endif
    @if($open)
    <div class='float-right float-end'>
        <a href='javascript:void(1)'
            onclick='console.log($(this).parent().parent().parent().find("{{$open}}").slideToggle())'
            class='btn btn-secondary btn-sm'>
            <i class="fas fa-caret-down"></i>
        </a>
    </div>
    @endif
</div>
