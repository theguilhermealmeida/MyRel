@if ($notification->read == 0)
    <span class="d-block p-2 bg-primary text-white">{{$notification->text}}</span>

@else
    <span class="d-block p-2 bg-secondary text-white">{{$notification->text}}</span>
@endif

<hr>