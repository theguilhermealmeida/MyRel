<div class="comment" >
<header>
    <h2>{{ $comment->user()->get()[0]->name }}</h2>
</header>
<div>{{ $comment->text }}</div>
<div>{{ $comment->date }}</div>
<section id="replies">
  @each('partials.reply', $comment->replies()->get(), 'reply')
</section>
</div>