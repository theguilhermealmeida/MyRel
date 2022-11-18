<div class="reply" style="font-size: 13px;;border-style: solid;color: Black">
<header>
    <h4 onclick="location.href='/user/{{ $reply->user()->get()[0]->id }}';" style="color: Orange;cursor:pointer">{{ $reply->user()->get()[0]->name }}<img src={{ $reply->user()->get()[0]->photo }} ></h2>
</header>
<div style="color: black">{{ $reply->text }}</div>
<div>{{ $reply->date }}</div>
</div>