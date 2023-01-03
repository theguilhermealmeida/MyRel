<ul class="nav nav-tabs" id="reactionTabs" role="tablist">
            @foreach(['Like','Dislike','Sad','Angry','Amazed'] as $key => $reactionType)
            <li class="nav-item">
                <a class="nav-link{{ $key == 0 ? ' active' : '' }}" id="{{ $reactionType }}-tab" data-toggle="tab" href="#{{ $reactionType }}" role="tab" aria-controls="{{ $reactionType }}" aria-selected="true">{{ ucfirst($reactionType) }}</a>
            </li>
            @endforeach
</ul>
<div class="tab-content" id="reactionTabsContent">
    @foreach(['Like','Dislike','Sad','Angry','Amazed'] as $key => $reactionType)
        <div class="tab-pane fade{{ $key == 0 ? ' show active' : '' }}" id="{{ $reactionType }}" role="tabpanel" aria-labelledby="{{ $reactionType }}-tab">
                @each('partials.reaction', $post->reactions()->where('type',$reactionType)->get(), 'reaction')
        </div>
    @endforeach
</div>