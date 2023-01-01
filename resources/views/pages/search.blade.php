@extends('layouts.app')

@section('title', 'Posts')

@section('content')

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="users" aria-selected="true">Usuários</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="posts-tab" data-toggle="tab" href="#posts" role="tab" aria-controls="posts" aria-selected="false">Posts</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="comments-tab" data-toggle="tab" href="#comments" role="tab" aria-controls="comments" aria-selected="false">Comentários</a>
  </li>
</ul>

<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">
  @each('partials.user', $users, 'user')
  </div>
  <div class="tab-pane fade" id="posts" role="tabpanel" aria-labelledby="posts-tab">
  @each('partials.post', $posts, 'post')
  </div>
  <div class="tab-pane fade" id="comments" role="tabpanel" aria-labelledby="comments-tab">

  @each('partials.comment', $comments, 'comment')
  </div>
</div>


<script>
$(function () {
  $('#myTab a:first').tab('show')
})
</script>


@endsection