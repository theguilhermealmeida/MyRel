@extends('layouts.app')

@section('title', $post->name)

@section('content')
    <article class="post" data-id="{{ $post->id }}" >

    <div class="post-header">
              <img src={{ $post->user()->get()[0]->photo }} class="post-profile-pic">
              <div class="post-header-info">
                  <h3><a href="/user/{{ $post->user()->get()[0]->id }}">{{ $post->user()->get()[0]->getName() }}</a></h3>
                  <p>{{ $post->date }}</p>
              </div>
              @can('update', $post)
              <button id="toggle_edit_post" class="mx-auto btn btn-primary ">Edit Post</button>
              @endcan
              @can('delete', $post)
                {!!Form::open(['url' => 'api/posts/' . $post->id, 'method' => 'delete'])!!}
                <button type="submit" class="mx-auto btn btn-danger ">Delete Post</button>
                {!!Form::close()!!}
              @endcan
          </div>
          <div class="post-body">
              <p>{{ $post->text }}</p>
              @if($post->photo !== null)
              <div style="
                    width: 100%;
                    border-radius: 1em;
                    height: 20em;
                    background-image: url({{ $post->photo }});
                    background-size: 100% 100%;"></div>
              @endif
          </a>
          <div class="post-footer">
              <span class="comment-label">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-message-circle-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M3 20l1.3 -3.9a9 8 0 1 1 3.4 2.9l-4.7 1"></path>
                  </svg>
                  <span>{{$post->comments()->count()}}</span>
              </span>
              <div class="reaction-holder">
                  <span class="reaction-label">
                      <span>👍🏻</span>
                      <span>{{$post->reactions()->where('type','Like')->count()}}</span>
                  </span>
                  <span class="reaction-label">
                      <span>👎🏻</span>
                      <span>{{$post->reactions()->where('type','Dislike')->count()}}</span>
                  </span>
                  <span class="reaction-label">
                      <span>😿</span>
                      <span>{{$post->reactions()->where('type','Sad')->count()}}</span>
                  </span>
                  <span class="reaction-label">
                      <span>😡</span>
                      <span>{{$post->reactions()->where('type','Angry')->count()}}</span>
                  </span>
                  <span class="reaction-label">
                      <span>😍</span>
                      <span>{{$post->reactions()->where('type','Amazed')->count()}}</span>
                  </span>
              </div>
          </div>
          @if (Auth::check())
                    <button id="toggle_create_comment" class="mx-auto btn btn-primary">Comment</button>
            @endif

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
                    <img class="m-3" id="output"/>
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
        <section id="reactions" style="margin-top:30px;">
            <h2>Reactions</h2>
            @each('partials.reaction', $post->reactions()->get(), 'reaction')
        </section>
    </article>
@endsection
