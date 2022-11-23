@extends('layouts.app')

@section('title', 'Posts')

@section('content')


<section id="users">
    <h2>USERS</h2>
  @each('partials.user', $users, 'user')
</section>

<section id="posts">
    <h2>POSTS</h2>
  @each('partials.post', $posts, 'post')
</section>

<section id="comments">
    <h2>COMMENTS</h2>
  @each('partials.comment', $comments, 'comment')
</section>

@endsection