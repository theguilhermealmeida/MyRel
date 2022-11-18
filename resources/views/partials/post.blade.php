<article style="border-style: solid;color: Tomato">
<header>
    <h3 onclick="location.href='/user/{{ $post->user()->get()[0]->id }}'" style="color:DodgerBlue;cursor:pointer">{{ $post->user()->get()[0]->name }}<img src={{ $post->user()->get()[0]->photo }}</h2>
</header>
<div onclick="location.href='/posts/{{ $post->id }}';" class="post" data-id="{{ $post->id }}" style="border-style: solid;cursor: pointer;color: Green;margin:1em">
    {{ $post->text }}
</div>
</article>