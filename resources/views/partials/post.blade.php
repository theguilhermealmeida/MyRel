<article class="post" data-id="{{ $post->id }}">
<header>
    <h2><a href="/posts/{{ $post->id }}">{{ $post->user()->get()[0]->name }}</a></h2>
</header>
<div>{{ $post->text }}</div>
</article>