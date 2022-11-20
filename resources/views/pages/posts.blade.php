@extends('layouts.app')

@section('title', 'Posts')

@section('content')

<section id="search">
    <?php
      echo Form::open(array('url' => '/search', 'method' => 'get'));
      echo "Search:";
      echo Form::text('search');
      echo Form::button('Search', array('type' => 'submit'));
      echo Form::close();
    ?>
</section>

<section id="new_post">
  <article style="border-style: solid;color: Tomato">
    <header>
      <h2 onclick="location.href='/user/{{Auth::user()->id}}';" style="cursor:pointer;" ><img src={{ Auth::user()->photo }}</a></h2>
    </header>
    <?php
      echo Form::open(array('url' => 'api/posts', 'method' => 'put'));
      echo Form::textarea('text', 'Share your thoughts with us...');
      echo "Visibility";
      echo Form::select('visibility', array('Close Friends' => 'Close Friends', 'Friends' => 'Friends', 'Family' => 'Family', 'Strangers' => 'Strangers'));
      echo Form::button('Create new Post', array('type' => 'submit'));
      echo Form::close();
    ?>
    </article>
</section>

<section id="posts">
  @each('partials.post', $posts, 'post')
</section>

@endsection