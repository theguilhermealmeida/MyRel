<div class="comment" >
<header>
    <h2>{{ $comment->user()->get()[0]->name }}</h2>
</header>
<div>{{ $comment->text }}</div>
<div>{{ $comment->date }}</div>
</div>