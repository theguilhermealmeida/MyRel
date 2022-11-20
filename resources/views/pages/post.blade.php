@extends('layouts.app')

@section('title', $post->name)

@section('content')
    <article class="post" data-id="{{ $post->id }}" style="border-style: solid;color: Tomato">
        <header>
            <h2 onclick="location.href='/user/{{ $post->user()->get()[0]->id }}';" style="cursor:pointer;">
                {{ $post->user()->get()[0]->name }}</a><img src={{ $post->user()->get()[0]->photo }}></h2>
        </header>
        <div style="border-style: solid;color: Blue">{{ $post->text }}</div>

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

        <img src={{ $post->photo }}>
        <div>{{ $post->date }}</div>
        <section id="reactions" style="border-style: solid;color: Blue">
            <h2 style="color: Green">Reactions</h2>
            @each('partials.reaction', $post->reactions()->get(), 'reaction')
        </section>
        <section id="comments" style="border-style: solid;color: Purple">
            <h2 style="color: Blue">COMMENTS</h2>
            @each('partials.comment', $post->comments()->get(), 'comment')
        </section>
    </article>
@endsection
