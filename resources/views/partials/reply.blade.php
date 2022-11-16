<div class="reply" >
<header>
    <h3>{{ $reply->user()->get()[0]->name }}</h2>
</header>
<div>{{ $reply->text }}</div>
<div>{{ $reply->date }}</div>
</div>