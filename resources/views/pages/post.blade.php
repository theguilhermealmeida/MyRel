@extends('layouts.app')

@section('title', $post->name)

@section('content')
    <article class="post" data-id="{{ $post->id }}" >

    <div class="post-header-main">

            <div>

                <img src={{ $post->user()->get()[0]->photo }} class="post-profile-pic" alt=id={{ $post->user()->get()[0]->id }}>
                <div class="post-header-info">
                    <h3><a href="/user/{{ $post->user()->get()[0]->id }}">{{ $post->user()->get()[0]->getName() }}</a></h3>
                    <p>{{ $post->date }}</p>
                </div>
            </div>

<div>


    @can('update', $post)
    <button id="toggle_edit_post" class="mx-auto btn btn-primary ">Edit Post</button>
    @endcan

    @can('delete', $post)
    &nbsp; &nbsp; &nbsp;
      {!!Form::open(['url' => 'api/posts/' . $post->id, 'method' => 'delete'])!!}
      <button type="submit" class="mx-auto btn btn-danger ">Delete Post</button>
      {!!Form::close()!!}
    @endcan
</div>

        
          </div>
          @can('update', $post)
            <div style="display:none" id="edit_post" class="post card mb-3">
            {!!Form::open(['url' => 'api/posts/' . $post->id, 'method' => 'post','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'edit_post_form']) !!}
            {!! Form::token() !!}
                <div class="form-group">
                    <div>
                    <textarea required name="text" onkeyup="countChars(this,document.getElementById('charNumTextEdit'),280);" maxlength="280" id="edit_post_text" class="form-control" rows="5">{{ $post->text }}</textarea>
                    <p id="charNumTextEdit">0 characters</p>
                    </div>
                </div>
                <div class="form-group">
                    <div>
                    <input style="color:black;background-color:white" id="edit_post_picture" type="file" accept="image/*" class="form-control" name="image" onchange="loadFile(event)">
                    <img class="m-3" id="output" alt=""/>
                    </div>
                </div>
                <div class="form-group">
                    <div>
                    <select required name ="visibility" id="edit_post_visibility" data-visibility="{{$post->visibility}}" class="selectpicker" data-width="60%">
                    <option>Close Friends</option>
                    <option>Friends</option>
                    <option>Family</option>
                    <option>Strangers</option>
                    </select>
                    <button type="submit" class="btn btn-primary float-right">Edit</button>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
        @endcan
          <div class="post-body">
              <p>{{ $post->text }}</p>
              @if($post->photo !== null)
              <div style="
                    width: 100%;
                    border-radius: 1em;
                    height: 22em;
                    background-image: url({{ $post->photo }});
                    background-size: cover;
  background-position: center;"></div>
              @endif
          </a>
          <div class="post-footer">
              <span style="cursor:default; " class="comment-label">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-message-circle-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M3 20l1.3 -3.9a9 8 0 1 1 3.4 2.9l-4.7 1"></path>
                  </svg>
                  <span>{{$post->comments()->count()}}</span>
              </span>
              
        

              <div class="reaction-holder">
                <?php
                    $reaction = $post->reactions()
                        ->where('user_id', auth()->id())
                        ->first();
            
                    if ($reaction) {
                        $type = $reaction->type;
                    } else {
                        $type = null;
                    }
                ?>
            
            
            
                    {!!Form::open(['route' => ['addReaction', $post->id], 'method' => 'post','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'add_reaction_form']) !!}
                    {!!Form::token()!!}
                <span class="reaction-label {{ $type == 'Like' ? 'user-reaction' : '' }}">
                        <button class="btn btn-outline-secondary reaction-label" type='submit' name='reaction_type' value='Like'>👍🏻</button>
                    <span class=reaction-count>{{$post->reactions()->where('type','Like')->count()}}</span>
                </span>
                    {!!Form::close()!!}                    
                    {!!Form::open(['route' => ['addReaction', $post->id], 'method' => 'post','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'add_reaction_form']) !!}
                    {!!Form::token()!!}
                <span class="reaction-label {{ $type == 'Dislike' ? 'user-reaction' : '' }}">
                        <button class="btn btn-outline-secondary reaction-label" type='submit' name='reaction_type' value='Dislike'>👎🏻</button>
                    {!!Form::close()!!}
                    <span class=reaction-count>{{$post->reactions()->where('type','Dislike')->count()}}</span>
                </span>
                    {!!Form::open(['route' => ['addReaction', $post->id], 'method' => 'post','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'add_reaction_form']) !!}
                    {!!Form::token()!!}
                <span class="reaction-label {{ $type == 'Sad' ? 'user-reaction' : '' }}">
                        <button class="btn btn-outline-secondary reaction-label" type='submit' name='reaction_type' value='Sad'>😿</button>
                    {!!Form::close()!!}
                    <span class=reaction-count>{{$post->reactions()->where('type','Sad')->count()}}</span>
                </span>
                    {!!Form::open(['route' => ['addReaction', $post->id], 'method' => 'post','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'add_reaction_form']) !!}
                    {!!Form::token()!!}
                <span class="reaction-label {{ $type == 'Angry' ? 'user-reaction' : '' }}">
                        <button class="btn btn-outline-secondary reaction-label" type='submit' name='reaction_type' value='Angry'>😡</button>
                    {!!Form::close()!!}
                    <span class=reaction-count>{{$post->reactions()->where('type','Angry')->count()}}</span>
                </span>
                    {!!Form::open(['route' => ['addReaction', $post->id], 'method' => 'post','enctype' => 'multipart/form-data','class'=>'form-horizontal','id'=>'add_reaction_form']) !!}
                    {!!Form::token()!!}
                <span class="reaction-label {{ $type == 'Amazed' ? 'user-reaction' : '' }}">
                        <button class="btn btn-outline-secondary reaction-label" type='submit' name='reaction_type' value='Amazed'>😍</button>
                    {!!Form::close()!!}
                    <span class=reaction-count>{{$post->reactions()->where('type','Amazed')->count()}}</span>
                </span>
            </div>
          </div>

          <div class="card">
            <!-- Button to refresh the tabbed reaction list -->
            <!-- Refresh symbol -->
            
            <div class="card-header">
                <button class="btn btn-secondary ReactionsButton">Reactions</button>
                <button style="display:none" id="refresh-reaction-list-btn" type="button" class="btn btn-secondary ml-auto" data-post-id="{{ $post->id }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg> 
                </button>
            </div>
            <div class="show_post_reactions" id="reactionTabsContent">
                <!-- Tabs -->
                <ul class="nav nav-tabs" role="tablist">
                @foreach(['Like','Dislike','Sad','Angry','Amazed'] as $key => $reactionType)
                    <li class="nav-item">
                        <a class="nav-link{{ $key == 0 ? ' active' : '' }}" id="{{ $reactionType }}-tab" data-toggle="tab" href="#{{ $reactionType }}" role="tab" aria-controls="{{ $reactionType }}" aria-selected="true">{{ ucfirst($reactionType) }}</a>
                    </li>
                @endforeach
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    @foreach(['Like','Dislike','Sad','Angry','Amazed'] as $key => $reactionType)
                        <div class="tab-pane fade{{ $key == 0 ? ' show active' : '' }}" id="{{ $reactionType }}" role="tabpanel" aria-labelledby="{{ $reactionType }}-tab">
                            @each('partials.reaction', $post->reactions()->where('type',$reactionType)->get(), 'reaction')
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

          <hr>


        @if (Auth::check())
                    <button id="toggle_create_comment" class="mx-auto btn btn-primary" style="margin-bottom:30px;">Comment</button>
        @endif
            <div style="display:none" id="create_comment" class="post card mb-3">
            {!!Form::open(['url' => 'api/comments', 'method' => 'PUT','class'=>'form-horizontal','id'=>'create_comment_form']) !!}
            {!! Form::token() !!}
                <div class="form-group">
                    <div>
                    <textarea required name="text" onkeyup="countChars(this,document.getElementById('charNumTextComment'),280);" placeholder="Share your opinion on this post..." maxlength="280" class="form-control" rows="5"></textarea>
                    <p id="charNumTextComment">0 characters</p>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary float-right">Comment</button>
                </div>
                <input type="hidden" id="postId" name="postId" value="{{$post->id}}" />
            {!! Form::close() !!}
            </div>
        <section id="comments" >
            @each('partials.comment', $post->comments()->get(), 'comment')
        </section>
    </article>
@endsection
