<div class="comment" style="border-style:dashed;color: Tomato">
<header>
    <h2 onclick="location.href='/user/{{ $comment->user()->get()[0]->id }}';" style="color: Red;cursor:pointer">{{ $comment->user()->get()[0]->name }}<img src={{ $comment->user()->get()[0]->photo }} ></h2>
</header>
<div style="border-style: solid;color: Turquoise">{{ $comment->text }}</div>
<div>{{ $comment->date }}</div>
<section id="replies" style="border-style:dashed;color: lightBlue">
  <h3 style="color: Green">Replies</h3>
  @each('partials.reply', $comment->replies()->get(), 'reply')
</section>
</div>