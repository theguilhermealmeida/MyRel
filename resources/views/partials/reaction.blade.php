<li class="list-group-item d-flex align-items-center">
    <img src={{ $reaction->user()->get()[0]->photo }} class="rounded-circle mr-2" width="50" height="50" alt=id={{ $reaction->user()->get()[0]->id }}>
    @if($reaction->type=='Like')
        <span class="reaction mr-2">👍🏻</span>
    @endif
    @if($reaction->type=='Dislike')
        <span class="reaction mr-2">👎🏻</span>
    @endif
    @if($reaction->type=='Sad')
        <span class="reaction mr-2">😿</span>
    @endif
    @if($reaction->type=='Angry')
        <span class="reaction mr-2">😡</span>
    @endif
    @if($reaction->type=='Amazed')
        <span class="reaction mr-2">😍</span>
    @endif
    <a href="/user/{{ $reaction->user()->get()[0]->id }}">{{ $reaction->user()->get()[0]->name }}</a>
</li>