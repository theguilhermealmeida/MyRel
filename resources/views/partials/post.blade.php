<article style="border-style: solid;color: Tomato">
<header>
    <h3 onclick="location.href='/user/{{ $post->user()->get()[0]->id }}'" style="color:DodgerBlue;cursor:pointer">{{ $post->user()->get()[0]->getName() }}<img src={{ $post->user()->get()[0]->photo }}</h2>
</header>
<div onclick="location.href='/posts/{{ $post->id }}';" class="post" data-id="{{ $post->id }}" style="border-style: solid;cursor: pointer;color: Green;margin:1em">
    {{ $post->text }}
</div>
<div>Number of comments:{{$post->comments()->count()}} 
</div>
<div>Number of reactions:{{$post->reactions()->count()}} 
Like:{{$post->reactions()->where('type','Like')->count()}}
Dislike:{{$post->reactions()->where('type','Dislike')->count()}}
Sad:{{$post->reactions()->where('type','Sad')->count()}}
Angry:{{$post->reactions()->where('type','Angry')->count()}}
Amazed:{{$post->reactions()->where('type','Amazed')->count()}}
</div>
</article>