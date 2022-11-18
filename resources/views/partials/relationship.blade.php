<div class="relationship" style="border-style: solid;color: Blue">
<header>
    <h4 onclick="location.href='/user/{{ $relationship->id }}'" style="color: Orange;cursor:pointer">{{$relationship->name}}<img src={{ $relationship->photo }} ></h4>
</header>
<div >{{$relationship->pivot->type}}</div>
<div >{{$relationship->pivot->state}}</div>
</div>