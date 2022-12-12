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
          </div>
          <a class="post-body" href="/posts/{{ $post->id }}">
              <p>{{ $post->text }}</p>
              <div style="
                    width: 100%;
                    border-radius: 1em;
                    height: 20em;
                    background-image: url({{ $post->photo }});
                    background-size: 100% 100%;"></div>
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

        @can('update', $post)
            <button onclick="toggleEditPostPopUp()">Edit post</button>
            <div class="popup" id="popup1">
                <div class='overlay'></div>
                <div class='content'>
                    <div class="close-btn" onclick="toggleEditPostPopUp()">&times;
                    </div>
                    <?php
                    echo Form::open(['url' => 'api/posts/' . $post->id, 'method' => 'post']);
                    echo Form::text('text', $post->text, array('required'));
                    echo 'Visibility';
                    echo Form::select('visibility', ['Close Friends' => 'Close Friends', 'Friends' => 'Friends', 'Family' => 'Family', 'Strangers' => 'Strangers']);
                    echo Form::button('Edit Post', ['type' => 'submit']);
                    echo Form::close();
                    ?>
                </div>
            </div>
        @endcan
        @can('delete', $post)
            <?php
            echo Form::open(['url' => 'api/posts/' . $post->id, 'method' => 'delete']);
            echo Form::button('Delete Post', ['type' => 'submit']);
            echo Form::close();
            ?>
        @endcan




        <section id="reactions" style="margin-top:30px;">
            <h2>Reactions</h2>
            @each('partials.reaction', $post->reactions()->get(), 'reaction')
        </section>
        <section id="comments" >
            <h2>COMMENTS</h2>
            @each('partials.comment', $post->comments()->get(), 'comment')
        </section>
    </article>
@endsection
