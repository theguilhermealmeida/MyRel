@extends('layouts.app')

@section('title', $post->name)

@section('content')
    <article class="post" data-id="{{ $post->id }}" >

    <div class="post card" >
          <div class="post-header">
              <img src={{ $post->user()->get()[0]->photo }} class="post-profile-pic">
              <div class="post-header-info">
        
                  <h3><a href="/user/{{ $post->user()->get()[0]->id }}">{{ $post->user()->get()[0]->getName() }}</a></h3>
                  <div>{{ $post->date }}</div>
              </div>
          </div>
          <a class="post-body" href="/posts/{{ $post->id }}">
              <p>{{ $post->text }}</p>
              <img src={{ $post->photo }}>

          </a>

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
                    echo Form::textarea('text', $post->text);
                    echo 'Visibility';
                    echo Form::select('visibility', ['Close Friends' => 'Close Friends', 'Friends' => 'Friends', 'Family' => 'Family', 'Strangers' => 'Strangers'], $post->visibility);
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
