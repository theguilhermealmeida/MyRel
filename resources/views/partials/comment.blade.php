<div class="post card" data-id="{{ $comment->id }}">
    <div class="post-header">
        <img src={{ $comment->user()->get()[0]->photo }} class="post-profile-pic" alt=id={{ $comment->user()->get()[0]->id }}>
        <div class="post-header-info">
            <h3><a href="/user/{{ $comment->user()->get()[0]->id }}">{{ $comment->user()->get()[0]->name }}</a></h3>
            <p>{{ $comment->date }}</p>
        </div>
        @can('update', $comment)
        <button class="toggle_edit_comment mx-auto btn btn-primary btn-sm">Edit Comment</button>
        @endcan
        @can('delete', $comment)
        {!!Form::open(['url' => 'api/comments/' . $comment->id, 'method' => 'delete'])!!}
            <button type="submit" class="mx-auto btn btn-danger btn-sm">Delete Comment</button>
        {!!Form::close()!!}
        @endcan
    </div>
    @can('update', $comment)
    <div style="display:none" id="edit_comment{!!$comment->id!!}" class="post card mb-3">
        {!!Form::open(['url' => 'api/comments/' . $comment->id, 'method' => 'post','class'=>'form-horizontal','id'=>'edit_comment_form']) !!}
            {!! Form::token() !!}
            <div class="form-group">
                <div>
                    <textarea required name="text" onkeyup="countChars(this,document.getElementById('charNumCommentEdit'),280);" maxlength="280" id="edit_comment_text" class="form-control" rows="5">{{ $comment->text }}</textarea>
                    <p id="charNumCommentEdit">0 characters</p>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary float-right">Edit</button>
            </div>
        {!! Form::close() !!}
    </div>
    @endcan
    <a class="post-body">
        <p>{{ $comment->text }}</p>
    </a>
    <div class="post-footer">
        <button style="background: transparent;border: none !important;" class="comment-label">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-message-circle-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M3 20l1.3 -3.9a9 8 0 1 1 3.4 2.9l-4.7 1"></path>
            </svg>
            <span>{{$comment->replies()->count()}}</span>
        </button>
        <div class="reaction-holder">
        <?php
        $reaction = $comment->reactions()
                    ->where('user_id', auth()->id())
                    ->first();
        if ($reaction) {
            $type = $reaction->type;
        } else {
            $type = null;
        }
        ?>
            {!!Form::open(['route' => ['addCommentReaction', $comment->id], 'method' => 'post','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'add_commentreaction_form']) !!}
                {!!Form::token()!!}
                <span class="reaction-label {{ $type == 'Like' ? 'user-reaction' : '' }}">
                    <button class="btn btn-outline-secondary reaction-label" type='submit' name='reaction_type' value='Like'>👍🏻</button>
                    <span class=reaction-count>{{$comment->reactions()->where('type','Like')->count()}}</span>
                </span>
            {!!Form::close()!!}                    
            {!!Form::open(['route' => ['addCommentReaction', $comment->id], 'method' => 'post','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'add_commentreaction_form']) !!}
                {!!Form::token()!!}
                <span class="reaction-label {{ $type == 'Dislike' ? 'user-reaction' : '' }}">
                    <button class="btn btn-outline-secondary reaction-label" type='submit' name='reaction_type' value='Dislike'>👎🏻</button>
                    <span class=reaction-count>{{$comment->reactions()->where('type','Dislike')->count()}}</span>
                </span>
            {!!Form::close()!!}
            {!!Form::open(['route' => ['addCommentReaction', $comment->id], 'method' => 'post','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'add_commentreaction_form']) !!}
                {!!Form::token()!!}
                <span class="reaction-label {{ $type == 'Sad' ? 'user-reaction' : '' }}">
                    <button class="btn btn-outline-secondary reaction-label" type='submit' name='reaction_type' value='Sad'>😿</button>
                    <span class=reaction-count>{{$comment->reactions()->where('type','Sad')->count()}}</span>
                </span>
            {!!Form::close()!!}
            {!!Form::open(['route' => ['addCommentReaction', $comment->id], 'method' => 'post','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'add_commentreaction_form']) !!}
                {!!Form::token()!!}
                <span class="reaction-label {{ $type == 'Angry' ? 'user-reaction' : '' }}">
                    <button class="btn btn-outline-secondary reaction-label" type='submit' name='reaction_type' value='Angry'>😡</button>
                    <span class=reaction-count>{{$comment->reactions()->where('type','Angry')->count()}}</span>
                </span>
            {!!Form::close()!!}
            {!!Form::open(['route' => ['addCommentReaction', $comment->id], 'method' => 'post','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'add_commentreaction_form']) !!}
                {!!Form::token()!!}
                <span class="reaction-label {{ $type == 'Amazed' ? 'user-reaction' : '' }}">
                    <button class="btn btn-outline-secondary reaction-label" type='submit' name='reaction_type' value='Amazed'>😍</button>
                <span class=reaction-count>{{$comment->reactions()->where('type','Amazed')->count()}}</span>
                </span>
            {!!Form::close()!!}
        </div>
    </div>
    <div class="post-create-reply">
        @if (Auth::check())
        <button id="toggle_create_reply" class="toggle_create_reply mx-auto btn btn-primary btn-sm">Reply</button>
        @endif
        <div style="display:none" id="create_reply" class="post card mb-3">
            {!!Form::open(['url' => 'api/replies', 'method' => 'PUT','class'=>'form-horizontal','id'=>'create_reply_form']) !!}
                {!! Form::token() !!}
                <div class="form-group">
                    <div>
                        <textarea required name="text" onkeyup="countChars(this,document.getElementById('charNumTextReply'),280);" placeholder="Share your opinion on this comment..." maxlength="280" class="form-control" rows="5"></textarea>
                        <p id="charNumTextReply">0 characters</p>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary float-right">Reply</button>
                </div>
                <input type="hidden" id="commentId" name="commentId" value="{{$comment->id}}" />
            {!! Form::close() !!}
        </div>
    </div>
    <div class="post-replies">
        <section class="replies ml-2">
            @each('partials.reply', $comment->replies()->get(), 'reply')
        </section>
    </div>
</div>
