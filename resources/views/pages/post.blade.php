@extends('layouts.app')

@section('title', $post->name)

@section('content')
<article class="post" data-id="{{ $post->id }}">
<header>
  <h2>{{ $post->user()->get()[0]->name }}</a></h2>
</header>
<div>{{ $post->text }}</div>
<img src={{ $post->photo }} > 
<div>{{ $post->date }}</div>
<section id="comments">
  @each('partials.comment', $post->comments()->get(), 'comment')
</section>
</article>
@endsection