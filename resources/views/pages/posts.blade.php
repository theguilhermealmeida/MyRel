@extends('layouts.app')

@section('title', 'Posts')

@section('content')
<h1>Your Feed</h1>
<hr>


<section id="posts">
  @each('partials.post', $posts, 'post')
</section>

@endsection